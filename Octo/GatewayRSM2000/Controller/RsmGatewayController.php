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
use Octo\System\Model\Log;
use Octo\Template;

class RsmGatewayController extends Controller
{
    /** @var \Octo\Invoicing\Store\LineItemStore */
    protected $lineItemStore;

    /* $_POST 'uniqueid', 'donation', 'purchase' = total (subtotal + shipping_cost)*/
    public function success()
    {
        /** @type \Octo\Invoicing\Store\InvoiceStore */
        $invoiceStore = Store::get('Invoice');

        /** @type \Octo\Invoicing\Model\Invoice */
        $invoice = $invoiceStore->getById($this->getParam('uniqueid'));

        if ($invoice) {
            $invoiceService = $this->getInvoiceService();
            $invoiceService->registerPayment($invoice, $this->getParam('purchase'));
            //TODO: Add paid donation

            //Clear Basket
            $this->lineItemStore = Store::get('LineItem');
            $items = $this->lineItemStore->getByInvoiceId($invoice->getId());
            foreach($items as $item) {

                if(is_numeric($item->Basket->getId())) {
                    $this->lineItemStore->emptyShopBasket($item->Basket);
                }
            }

            die('<script>top.window.location.href="/checkout/thanks/'.$invoice->getId().'";</script>');
        }

        header('Location: /checkout/failed');
        die;
    }

    /* $_POST 'acccountno', Array of errors and error codes errors[0][code] errors[0][message]*/
    public function failed()
    {
        $message = 'There were some problem.';
        $class = 'warning';
        $errors = $this->getParam('errors', null);

        if (!empty($errors) && count($errors)>0) {
            $errorCode = $errors[0]['code'];
            $errorMessage = $errors[0]['message'];
        }

        $invoice_id = $this->getParam('acccountno', null);

        if (!empty($errorCode)) {
            $this->logRSM2000Errors($invoice_id, $errorCode .': '.$errorMessage);
        }

        //Error: 1014 - Unique ID has been used before. /Invoice is paid?
        if (!empty($errorCode) && ((int)$errorCode == 1014) && !empty($invoice_id)) {
                /** @type \Octo\Invoicing\Store\InvoiceStore */
                $invoiceStore = Store::get('Invoice');

                /** @type \Octo\Invoicing\Model\Invoice */
                $invoice = $invoiceStore->getById($invoice_id);

                if ($invoice && ($invoice->getTotal() <= $invoice->getTotalPaid())) {
                    $class = 'success';
                    $message = 'That invoice is marked as paid.';
                }

        }
        echo '<div class="alert alert-'.$class.'" role="alert">'.$message.'</div>';
    }

    /**
     * Log errors from RSM2000 to DB
     * @param int $invoice_id
     * @param string $errorMessage
     */
    protected function logRSM2000Errors($invoice_id=0, $errorMessage='')
    {
        $log = Log::create(Log::TYPE_ERROR, 'rsm2000', $errorMessage);
        $log->setUser(1);
        $log->setScopeId($invoice_id);
        $log->setLink('rsm-gateway/failed');
        $log->save();
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
