<?php

namespace Octo\GatewayRSM2000\Controller;

use b8\Exception\HttpException\NotFoundException;
use b8\Form;
use Katzgrau\KLogger\Logger;
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
use Psr\Log\LogLevel;

class RsmGatewayController extends Controller
{
    /** @var \Octo\Invoicing\Store\LineItemStore */
    protected $lineItemStore;
    /**
     * @var \Octo\Invoicing\Store\InvoiceStore
     */
    protected $invoiceStore;


    /* $_POST 'IdentityCheck', 'uniqueid', 'donation', 'purchase' = total (subtotal + shipping_cost)*/
    public function successCallback()
    {
        if ($this->config->get('debug.rsm')) {
            $log = new Logger($this->config->get('logging.directory') . 'rsm2000/', LogLevel::DEBUG);
            $log->debug('SuccessCallback, POST=: ', $this->getParams());
        }


        $identityCheck = $this->getParam('IdentityCheck', null);
        $postData = $this->getParams();
        unset($postData['IdentityCheck']);
        $rawPost = http_build_query($postData);

        $sha1 = sha1(urldecode($rawPost) . $this->config->get('rsm.key'));

        if ($sha1 == $identityCheck) {

            $this->invoiceStore = Store::get('Invoice');

            /** @type \Octo\Invoicing\Model\Invoice $invoice */
            $invoice = $this->invoiceStore->getById($this->getParam('uniqueid'));

            if ($invoice) {
                $invoiceService = $this->getInvoiceService();
                $invoiceService->registerPayment($invoice, $this->getParam('purchase'));

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

                //TODO: Clear unpaid Invoices with the same basketId

            }

        } else {
            $log = new Logger($this->config->get('logging.directory') . 'rsm2000/', LogLevel::DEBUG);
            $log->debug('Sha1:: ' . $sha1 . ' should be equal to IdentityCheck:: ' . $identityCheck);
        }


        die;
    }


    public function failedCallback()
    {
        //TODO: write to DB log with failed payment
        if ($this->config->get('debug.rsm')) {
            $log = new Logger($this->config->get('logging.directory') . 'rsm2000/', LogLevel::DEBUG);
            $log->debug('Failed Callback, POST=: ', $this->getParams());
        }
        die;
    }



    /* $_POST 'uniqueid', 'donation', 'purchase' = total (subtotal + shipping_cost)*/
    /*WatchOut: Here they post firstname not forename*/
    public function success()
    {
        if ($this->config->get('debug.rsm')) {
            $log = new Logger($this->config->get('logging.directory') . 'rsm2000/', LogLevel::DEBUG);
            $log->debug('Success Redirect POST=: ', $this->getParams());
        }

        $this->invoiceStore = Store::get('Invoice');

        /** @type \Octo\Invoicing\Model\Invoice $invoice */
        $invoice = $this->invoiceStore->getById($this->getParam('uniqueid'));

        if ($invoice && ($invoice->getTotalPaid() == $invoice->getTotal())) {

            session_start();
            $_SESSION['title'] = $this->getParam('title');
            $_SESSION['firstname'] = $this->getParam('firstname', '');
            $_SESSION['surname'] = $this->getParam('surname', '');

            die('<script>top.window.location.href="/checkout/thanks/";</script>');
        }

        header('Location: /checkout/failed');
        die;
    }

    /* $_POST 'acccountno', Array of errors and error codes errors[0][code] errors[0][message]*/
    public function failed()
    {
        if ($this->config->get('debug.rsm')) {
            $log = new Logger($this->config->get('logging.directory') . 'rsm2000/', LogLevel::DEBUG);
            $log->debug('Failed redirection POST=: ', $this->getParams());
        }

        $message = 'There was a problem.';
        $class = 'warning';
        $errors = $this->getParam('errors', null);

        $invoice_id = $this->getParam('acccountno', null);

        if (!empty($errors) && count($errors)>0) {
            $errorCode = $errors[0]['code'];
            $errorMessage = $errors[0]['message'];
            $message = $errorMessage;
            $this->logRSM2000Errors($invoice_id, $errorCode .': '.$errorMessage);
        }


        //Error: 1014 - Unique ID has been used before. /Invoice is paid?
        if (!empty($errorCode) && !empty($invoice_id)) {
            $this->invoiceStore = Store::get('Invoice');

            /** @type \Octo\Invoicing\Model\Invoice $invoice */
            $invoice = $this->invoiceStore->getById($invoice_id);

            if ($errorCode >= 2000 && $errorCode < 3000) {
                $log = new Logger($this->config->get('logging.directory'), LogLevel::DEBUG);
                $log->debug('Contact validation failed for RSM2000: ', $this->getParams());
            }

            if ((int)$errorCode == 1014) {
                if ($invoice && ($invoice->getTotal() <= $invoice->getTotalPaid())) {
                    //$class = 'success';
                    //$message = 'That invoice is marked as paid.';

                    //Redirect to thanks page, as it looks like the invoice is paid.
                    die('<script>top.window.location.href="/checkout/thanks/'.$invoice->getUuid().'";</script>');
                } else {
                    //UniqueId cannot be use, Invoice not paid
                    $class = 'warning';
                    $message = '<p>Payment gateway will not process this transaction anymore.</p>';
                    $message .= '<p><a href="javascript:void(0)" onclick="top.window.location.href=\'/checkout/\';">Go to Checkout</a></p>';
                }
            } elseif ((int)$errorCode == 3001) {
            //3001: User cancelled transaction.
                $class = 'warning';
                $message = '<p>You cancelled transaction.</p>';
                $message .= '<p><a href="javascript:void(0)" onclick="top.window.location.href=\'/checkout/\';">Go to Checkout</a></p>';
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
