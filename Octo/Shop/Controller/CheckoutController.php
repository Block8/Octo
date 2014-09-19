<?php

namespace Octo\Shop\Controller;

use b8\Exception\HttpException\NotFoundException;
use b8\Form;
use HMUK\Utilities\PostageCalculator;
use Katzgrau\KLogger\Logger;
use Octo\BlockManager;
use Octo\Controller;
use Octo\Event;
use Octo\Form as FormElement;
use Octo\Invoicing\Model\Invoice;
use Octo\Invoicing\Service\InvoiceService;
use Octo\Shop\Store\ShopBasketStore;
use Octo\Shop\Service\ShopService;
use Octo\Store;
use Octo\Template;
use Octo\System\Model\Contact;
use Psr\Log\LogLevel;

class CheckoutController extends Controller
{
    /**
     * @var \Octo\System\Store\ContactStore
     */
    protected $contactStore;
    /**
     * @var \Octo\Invoicing\Store\InvoiceStore
     */
    protected $invoiceStore;

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
        $form = $this->detailsForm($basketId);

        if ($this->request->getMethod() == 'POST') {
            $myParams = $this->getParams();

            if(isset($myParams['same_as_billing']) && $myParams['same_as_billing'] == "1") {
                $myParams['shipping_address'] = $myParams['billing_address'];
            }

            $form->setValues($myParams);

            if($form->validate()) {
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
                /** @var \Octo\System\Model\Contact $contact */
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

                header('Location: /checkout/invoice/' . $invoice->getUuid());
                die;
            } else {
                $log = new Logger($this->config->get('logging.directory'), LogLevel::DEBUG);
                $log->debug('Contact validation failed: ', $form->getValues());
            }
        }
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

    public function invoice($invoiceUuid)
    {
        if (strlen($invoiceUuid) != Invoice::UUID_LENGTH) {
            header('Location: /');
            die;
        }

        $this->invoiceStore = Store::get('Invoice');
        /** @var \Octo\Invoicing\Model\Invoice $invoice */
        $invoice = $this->invoiceStore->getByUuid($invoiceUuid);

        if (is_null($invoice)) {
            throw new NotFoundException('There is no invoice with ID: ' . $invoiceUuid);
        }

        if ($invoice && $invoice->getInvoiceStatusId() != Invoice::STATUS_NEW) {
            //TODO: Add check date
            header('Location: /');
            die;
        }

        /** @var \Octo\Invoicing\Model\Item[] $invoiceItems */
        $invoiceItems = $this->getInvoiceService()->getItems($invoice);

        if (empty($invoiceItems)) {
            header('Location: /');
            die;
        }

        $view = Template::getPublicTemplate('Checkout/invoice');
        /** @var \Octo\Invoicing\Model\InvoiceAdjustment $adjustments */
        $adjustments = Store::get('InvoiceAdjustment')->getByInvoiceId($invoice->getId());


        $view->invoice = $invoice;
        $view->items = $invoiceItems;
        $view->adjustments = $adjustments;
        $view->billingAddress = json_decode($invoice->getBillingAddress(), true);
        $view->shippingAddress = json_decode($invoice->getShippingAddress(), true);

        $donations = 0;

        foreach($adjustments as $donation)
        {
            $donations += floatval($donation->getDisplayValue());
        }
        $view->total = $invoice->getSubtotal() + $invoice->getShippingCost() + $donations;
        $view->justevents = 0;

        //Payment
        if ($invoice->getTotal() > 0) {

            $invoiceData = [
                'payment_options' => [],
                'invoice' => $invoice,
                'items' => $view->items,
            ];

            Event::trigger('PaymentOptions', $invoiceData);
            $view->paymentOptions = $invoiceData['payment_options'];
        } elseif($this->isEligibleForFreePass($invoiceItems, $invoice->getTotal())){
            //Free items like tickets
            $view->justevents = 1;
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


    //Check Basket - if is not empty means there are items for free
    private function isEligibleForFreePass($invoiceItems, $invoiceTotal)
    {
        if ($invoiceTotal != 0 ) {
            return false;
        }

        return (count($invoiceItems) > 0);
    }


    /**
     * Accept paid=0.00 for free items like Tickets
     */
    public function proceedFreeTickets()
    {
        $this->invoiceStore = Store::get('Invoice');
        $purchase = (int)$this->getParam('purchase', 0);

        /** @type \Octo\Invoicing\Model\Invoice $invoice */
        $invoice = $this->invoiceStore->getByUuid($this->getParam('uniqueid'));

        session_start();
        $_SESSION['title'] = $invoice->getContact()->getTitle();
        $_SESSION['firstname'] = $invoice->getContact()->getFirstName();
        $_SESSION['surname'] = $invoice->getContact()->getLastName();

        if ($invoice->getInvoiceStatusId() != Invoice::STATUS_NEW) {
            //Do not need to proccess invoice anymore
            die('<script>top.window.location.href="/checkout/thanks/";</script>');
        }


        /** @var \Octo\Invoicing\Model\Item[] $invoiceItems */
        $invoiceItems = $this->getInvoiceService()->getItems($invoice);


        if ($invoice && $this->isEligibleForFreePass($invoiceItems, $invoice->getTotal()) && $purchase == 0) {
            $invoiceService = $this->getInvoiceService();
            $invoiceService->registerPayment($invoice, $purchase);

            //Clear Basket
            $this->lineItemStore = Store::get('LineItem');
            /** @type \Octo\Invoicing\Model\LineItem[]  $items */
            $items = $this->lineItemStore->getByInvoiceId($invoice->getId());

            foreach($items as $item) {
                $basket = $item->getBasket();
                if(!empty($basket) && is_numeric($basket->getId())) {
                    $this->lineItemStore->emptyShopBasket($item->Basket);
                }
            }


            die('<script>top.window.location.href="/checkout/thanks/";</script>');
        }

        header('Location: /checkout/failed');
        die;
    }

    /**
     * Redirection from RSM2000 with title, forename, surname after success payment
     * @return string
     */
    public function thanks()
    {
        session_start();

        if ($this->config->get('debug.rsm')) {
            $log = new Logger($this->config->get('logging.directory') . 'rsm2000/', LogLevel::DEBUG);
            $log->debug('Thanks page after success Redirect SESSION=: ', $_SESSION);
        }

        $view = Template::getPublicTemplate('Checkout/thanks');


        $view->title    = isset($_SESSION['title']) ? ucfirst($_SESSION['title']) : '';
        $view->firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : '';
        $view->surname  = isset($_SESSION['surname']) ? $_SESSION['surname'] : '';

        $blockManager = $this->getBlockManager($view);

        $output = $view->render();

        $data = [
            'output' => &$output,
            'datastore' => $blockManager->getDataStore(),
        ];

        Event::trigger('PageLoaded', $data);

        return $output;
    }

    public function failed()
    {
        if ($this->config->get('debug.rsm')) {
            $log = new Logger($this->config->get('logging.directory') . 'rsm2000/', LogLevel::DEBUG);
            $log->debug('Failed page after failed redirect POST=: ', $this->getParams());
        }

        $view = Template::getPublicTemplate('Checkout/failed');
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
        $countryCode = $billingAddress['country_code'];
        unset($billingAddress['postcode']);
        unset($billingAddress['country_code']);

        $contact['address'] = $billingAddress;
        $contact['postcode'] = $postcode;
        $contact['country_code'] = $countryCode;

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

        //RSM2000 2-10
        $name = new FormElement\Element\Title('title');
        $name->setLabel('Title');
        $name->setRequired(true);
        $fieldset->addField($name);

        //RSM2000 1-40 Alpha hyphens and single quotes
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
        $name->setPattern('^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,6})$');
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
                ['uri' => '/', 'title' => 'Shop', 'active' => false],
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

    /**
     * Clear Basket information from user Cookie
     */
    public function clearBasket()
    {
        setcookie('basket_id', '', time() - 1, '/');
        setcookie('basket_total', '', time() - 1, '/');
        setcookie('basket_items', '', time() - 1, '/');
        header('Location: /');
        die;
    }
}
