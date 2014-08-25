<?php

namespace Octo\Shop\Controller;

use b8\Exception\HttpException\NotFoundException;
use b8\Form;
use Octo\BlockManager;
use Octo\Controller;
use Octo\Event;
use Octo\Form as FormElement;
use Octo\Invoicing\Service\InvoiceService;
use Octo\Shop\Store\ShopBasketStore;
use Octo\Shop\Service\ShopService;
use Octo\Store;
use Octo\Template;

class CheckoutController extends Controller
{
    public function index()
    {
        $basketId = null;

        if (array_key_exists('basket_id', $_COOKIE)) {
            $basketId = $_COOKIE['basket_id'];
        }

        $shopService = $this->getShopService();
        $basket = $shopService->getBasket($basketId);

        $items = Store::get('LineItem')->getByBasketId($basket->getId());

        $view = Template::getPublicTemplate('Checkout/basket');
        $view->basket = $basket;
        $view->items = $items;

        $basketTotal = 0;

        foreach ($items as $item) {
            $basketTotal += $item->getLinePrice();
        }
        $basketTotal = number_format($basketTotal, 2, '.', '');

        $view->basketTotal = $basketTotal;
        $view->itemsCount = count($items);

        if ($this->request->isAjax() || $this->request->getParam('ajax')) {
            $return = [
                'basket' => $basket->toArray(),
                'items' => array_map(function ($item) { return $item->toArray(); }, $items),
                'total' => $basketTotal,
            ];

            return json_encode($return);
        }

        $blockManager = $this->getBlockManager($view);

        $output = $view->render();

        $data = [
            'output' => &$output,
            'datastore' => $blockManager->getDataStore(),
        ];

        Event::trigger('PageLoaded', $data);

        return $output;
    }

    public function details($basketId)
    {
        $this->contactStore = Store::get('Contact');

        if ($this->request->getMethod() == 'POST') {
            $contactDetails = $this->getContactDetails();
            $contact = $this->contactStore->findContact($contactDetails);

            if (is_null($contact)) {
                $contact = new Contact();
            }

            if ($contact->getIsBlocked()) {
                header('Location: /');
                die;
            }

            $contact->setValues($contactDetails);
            $contact = $this->contactStore->save($contact);

            $shopService = $this->getShopService();
            $invoiceService = $this->getInvoiceService();

            $basket = $shopService->getBasket($basketId);
            $invoice = $shopService->createInvoiceForBasket($invoiceService, $basket, $contact);

            $billingAddress = $this->request->getParam('billing_address', []);
            $shippingAddress = $this->request->getParam('shipping_address', []);

            if ($this->getParam('same_as_billing', false)) {
                $shippingAddress = $billingAddress;
            }

            $invoice = $invoiceService->updateInvoice(
                $invoice,
                $invoice->getTitle(),
                $invoice->getContact(),
                $invoice->getCreatedDate(),
                $invoice->getDueDate(),
                $billingAddress,
                $shippingAddress
            );

            $data = [
                'invoice' => $invoice,
                'params' => $this->getParams(),
                'invoice_service' => $invoiceService,
            ];

            Event::trigger('CheckoutDetailsSubmit', $data);

            header('Location: /checkout/invoice/' . $invoice->getId());
            die;
        }

        //TODO: LPP Check if basket is empty and redirect back.
        $form = $this->detailsForm($basketId);
        $view = Template::getPublicTemplate('Checkout/details');
        $view->form = $form;

        $blockManager = $this->getBlockManager($view);

        $output = $view->render();

        $data = [
            'output' => &$output,
            'datastore' => $blockManager->getDataStore(),
        ];

        Event::trigger('PageLoaded', $data);

        return $output;
    }

    public function invoice($invoiceId)
    {
        $invoice = Store::get('Invoice')->getById($invoiceId);

        if (is_null($invoice)) {
            throw new NotFoundException('There is no invoice with ID: ' . $invoiceId);
        }

        $view = Template::getPublicTemplate('Checkout/invoice');
        $view->invoice = $invoice;
        $view->items = $this->getInvoiceService()->getItems($invoice);
        $view->adjustments = Store::get('InvoiceAdjustment')->getByInvoiceId($invoice->getId());
        $view->billingAddress = json_decode($invoice->getBillingAddress(), true);
        $view->shippingAddress = json_decode($invoice->getShippingAddress(), true);

        $invoiceData = [
            'payment_options' => [],
            'invoice' => $invoice,
            'items' => $view->items,
        ];

        Event::trigger('PaymentOptions', $invoiceData);

        $view->paymentOptions = $invoiceData['payment_options'];

        $blockManager = $this->getBlockManager($view);

        $output = $view->render();

        $data = [
            'output' => &$output,
            'datastore' => $blockManager->getDataStore(),
        ];

        Event::trigger('PageLoaded', $data);

        return $output;
    }

    public function thanks($invoiceId)
    {
        $invoice = Store::get('Invoice')->getById($invoiceId);

        if (is_null($invoice)) {
            throw new NotFoundException('There is no invoice with ID: ' . $invoiceId);
        }

        $view = Template::getPublicTemplate('Checkout/thanks');
        $view->invoice = $invoice;
        $view->items = $this->getInvoiceService()->getItems($invoice);
        $view->billingAddress = json_decode($invoice->getBillingAddress(), true);
        $view->shippingAddress = json_decode($invoice->getShippingAddress(), true);

        $blockManager = $this->getBlockManager($view);

        $output = $view->render();

        $data = [
            'output' => &$output,
            'datastore' => $blockManager->getDataStore(),
        ];

        Event::trigger('PageLoaded', $data);

        return $output;
    }

    protected function getContactDetails()
    {
        $contact = [
            'email' => $this->request->getParam('email', null),
            'phone' => $this->request->getParam('phone', null),
            'title' => $this->request->getParam('title', null),
            'first_name' => $this->request->getParam('first_name', null),
            'last_name' => $this->request->getParam('last_name', null),
        ];

        // If the submitter has set a company name, use it.
        $company = $this->request->getParam('company', null);
        if (!empty($company)) {
            $contact['company'] = $company;
        }

        // Use the billing address as the contact address:
        $billingAddress = $this->request->getParam('billing_address', []);
        $postcode = $billingAddress['postcode'];
        unset($billingAddress['postcode']);

        $contact['address'] = $billingAddress;
        $contact['postcode'] = $postcode;

        return array_filter($contact);
    }

    protected function detailsForm($basketId)
    {
        $form = new FormElement();
        $form->setAction('/checkout/details/' . $basketId);
        $form->setMethod('POST');

        $fieldset = new Form\FieldSet('basic_details');
        $fieldset->setLabel('Your details');
        $form->addField($fieldset);

        $name = new FormElement\Element\Title('title');
        $name->setLabel('Title');
        $name->setRequired(true);
        $fieldset->addField($name);

        $name = new FormElement\Element\Name('name');
        $name->setRequired(true);
        $name->setLabel('Name');
        $fieldset->addField($name);

        $name = new Form\Element\Text('company');
        $name->setRequired(false);
        $name->setLabel('Company');

        $fieldset->addField($name);

        $name = new Form\Element\Email('email');
        $name->setRequired(true);
        $name->setLabel('Email Address');

        $fieldset->addField($name);

        $name = new FormElement\Element\Phone('phone');
        $name->setRequired(true);
        $name->setLabel('Phone Number');

        $fieldset->addField($name);

        $fieldset = new Form\FieldSet('billing_address');
        $fieldset->setLabel('Billing Address');
        $form->addField($fieldset);

        $name = new FormElement\Element\Address('billing_address');
        $name->setRequired(true);
        $fieldset->addField($name);

        $fieldset = new Form\FieldSet('shipping_address');
        $fieldset->setLabel('Shipping Address');
        $form->addField($fieldset);

        $name = new Form\Element\Checkbox('same_as_billing');
        $name->setLabel('Use my Billing Address');
        $name->setCheckedValue(1);
        $name->setValue(1);
        $fieldset->addField($name);
        $name = new FormElement\Element\Address('shipping_address');
        $fieldset->addField($name);

        $fieldset = new Form\FieldSet('review_and_submit');
        $form->addField($fieldset);
        $name = new Form\Element\Submit();
        $name->setValue('Continue &raquo;');
        $fieldset->addField($name);

        Event::trigger('CheckoutDetailsForm', $form);

        return $form;
    }

    protected function getShopService()
    {
        $itemStore = Store::get('Item');
        $lineStore = Store::get('LineItem');
        $variantStore = Store::get('ItemVariant');
        $basketStore = Store::get('ShopBasket');

        return new ShopService($itemStore, $lineStore, $variantStore, $basketStore);
    }

    protected function getInvoiceService()
    {
        $invoiceStore = Store::get('Invoice');
        $adjustmentStore = Store::get('InvoiceAdjustment');
        $itemStore = Store::get('Item');
        $lineStore = Store::get('LineItem');

        return new InvoiceService($invoiceStore, $adjustmentStore, $itemStore, $lineStore);
    }

    public function getBlockManager(&$template)
    {
        $dataStore = [
            'breadcrumb' => [
                ['uri' => '/checkout', 'title' => 'Checkout', 'active' => true],
            ]
        ];

        $blockManager = new BlockManager();
        $blockManager->setDataStore($dataStore);
        $blockManager->setRequest($this->request);
        $blockManager->setResponse($this->response);
        $blockManager->attachToTemplate($template);

        return $blockManager;
    }

    public function updateLineItem($itemId)
    {
        $quantity = $this->getParam('quantity', null);
        $remove = $this->getParam('remove', null);

        $lineStore = Store::get('LineItem');

        /** @type \Octo\Invoicing\Model\LineItem $lineItem */
        $lineItem = $lineStore->getById($itemId);

        if ($lineItem && !is_null($quantity)) {
            $lineItem->setQuantity((int)$quantity);
            $lineItem->setLinePrice($lineItem->getItemPrice() * $lineItem->getQuantity());
            $lineStore->save($lineItem);
        }

        if ($lineItem && !is_null($remove)) {
            $lineStore->delete($lineItem);
        }

        $items = $lineStore->getByBasketId($lineItem->getBasketId());
        $basketTotal = 0;

        foreach ($items as $item) {
            $basketTotal += $item->getLinePrice();
        }

        die(json_encode([
            'line_price' => number_format($lineItem->getLinePrice(), 2),
            'basket_total' => number_format($basketTotal, 2, '.', ''),
        ]));
    }

    public function clearBasket()
    {
        setcookie('basket_id', '', time() - 1, '/');
        header('Location: /');
        die;
    }
}
