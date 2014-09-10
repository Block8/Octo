<?php

namespace Octo\GatewayStripe\Controller;

use b8\Exception\HttpException\NotFoundException;
use b8\Form;
use Octo\Controller;
use Octo\Event;
use Octo\Form as FormElement;
use Octo\Invoicing\Model\Invoice;
use Octo\Invoicing\Service\InvoiceService;
use Octo\Shop\Store\ShopBasketStore;
use Octo\Shop\Service\ShopService;
use Octo\Store;
use Octo\Template;
use Stripe;
use Stripe_CardError;
use Stripe_Charge;

class StripeGatewayController extends Controller
{
    public function charge($invoiceId)
    {
        /**
         * @var \Octo\Invoicing\Store\InvoiceStore
         */
        $invoiceStore = Store::get('Invoice');

        /**
         * @var \Octo\Invoicing\Model\Invoice $invoice
         */
        $invoice = $invoiceStore->getById($invoiceId);

        Stripe::setApiKey("sk_test_mwc25DIIgxtbQFk3MFrbn9eA");

        try {
            $charge = Stripe_Charge::create(array(
                    "amount" => $invoice->getTotal() * 100, // amount in cents, again
                    "currency" => "gbp",
                    "card" => $this->getParam('stripeToken'),
                    "description" => $invoice->getContact()->getEmail())
            );
        } catch(Stripe_CardError $e) {
            header('Location: /checkout/invoice/' . $invoice->getUuid() . '?declined=1');
            die;
        }

        $invoice->setTotalPaid($invoice->getTotal());
        $invoice->setInvoiceStatusId(Invoice::STATUS_PAID);
        $invoice->setReference($charge->id);
        $invoice->setUpdatedDate(new \DateTime());

        $invoiceStore->save($invoice);

        header('Location: /checkout/thanks/' . $invoice->getUuid());
        die;
    }

    protected function getInvoiceService()
    {
        $invoiceStore = Store::get('Invoice');
        $adjustmentStore = Store::get('InvoiceAdjustment');
        $itemStore = Store::get('Item');
        $lineStore = Store::get('LineItem');

        return new InvoiceService($invoiceStore, $adjustmentStore, $itemStore, $lineStore);
    }
}