<?php
namespace Octo\GatewayStripe\Event;

use b8\Config;
use b8\Form;
use Octo\Event\Listener;
use Octo\Event\Manager;
use Octo\Store;
use Octo\Template;

class GatewayEvents extends Listener
{
    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('PaymentOptions', array($this, 'setupPaymentOption'));
    }

    public function setupPaymentOption(&$data)
    {
        $invoice =& $data['invoice'];

        $view = Template::getPublicTemplate('Shop/Gateway/Stripe');
        $view->invoice = $invoice;
        $view->invoiceTotal = $invoice->getTotal() * 100;
        $view->itemCount = count($data['items']);
        $view->billingAddress = json_decode($invoice->getBillingAddress(), true);
        $view->shippingAddress = json_decode($invoice->getShippingAddress(), true);

        $data['payment_options'][] = $view->render();
    }
}