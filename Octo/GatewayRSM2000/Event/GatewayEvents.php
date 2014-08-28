<?php
namespace Octo\GatewayRSM2000\Event;

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

        $view = Template::getPublicTemplate('Shop/Gateway/RSM2000');
        $view->invoice = $invoice;
        $view->itemCount = count($data['items']);
        $view->billingAddress = json_decode($invoice->getBillingAddress(), true);
        $view->shippingAddress = json_decode($invoice->getShippingAddress(), true);
        $view->uniqueid = md5($invoice->getId());

        /** @var \Octo\Invoicing\Model\InvoiceAdjustment[] $adjustments */
        $adjustments = Store::get('InvoiceAdjustment')->getByInvoiceId($invoice->getId());

        $view->donation = 0;
        $view->gift_aid = 'N';
        foreach ($adjustments as $adjustment) {
            if ($adjustment->getScope() == 'donation') {
                $view->donation = $adjustment->getDisplayValue();
                if ($adjustment->getGiftAid())
                {
                    $view->gift_aid = 'Y';
                }
            }
        }

        $data['payment_options'][] = $view->render();
    }
}