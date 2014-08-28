<?php

namespace Octo\GatewayRSM2000\Controller;

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

class RsmGatewayController extends Controller
{
    public function success()
    {
/* $_POST
'donation'
'purchase' = total (subtotal + shipping_cost)
*/

        /** @type \Octo\Invoicing\Store\InvoiceStore */
        $invoiceStore = Store::get('Invoice');

        /** @type \Octo\Invoicing\Model\Invoice */
        $invoice = $invoiceStore->getById($this->getParam('uniqueid'));


        if ($invoice) {
            $invoiceService = $this->getInvoiceService();
            $invoiceService->registerPayment($invoice, $this->getParam('purchase'));

            $lineItemStore = Store::get('LineItem');
            $items = $lineItemStore->getByInvoiceId($invoice->getId());
            foreach($items as $item) {
                echo $item->description;
                echo '<br />';
                if(isset($item->Basket)) {
                    $lineItemStore->emptyShopBasket($item->Basket);
                }
            }

//add payed donation
            // die('<script>top.window.location.href="/checkout/thanks/'.$invoice->getId().'";</script>');
        }

        header('Location: /checkout/failed');
        die;
    }

    public function failed()
    {

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
