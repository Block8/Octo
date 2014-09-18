<?php

namespace Octo\GatewayRSM2000\Controller;

use b8\Exception\HttpException\NotFoundException;
use b8\Form;
use Katzgrau\KLogger\Logger;
use Octo\Controller;
use Octo\Event;
use Octo\Form as FormElement;
use Octo\GatewayRSM2000\Model\Rsm2000Log;
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

        $this->logRSM2000Operation($this->getParams(), "callback", ($sha1 == $identityCheck));


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

        $this->logRSM2000Operation($this->getParams(), "callback");

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

        $this->logRSM2000Operation($this->getParams(), "redirect-success");

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

        die('<script>top.window.location.href="/checkout/failed/";</script>');
    }

    /* $_POST 'uniqueid', Array of errors and error codes errors[0][code] errors[0][message]*/
    public function failed()
    {
        if ($this->config->get('debug.rsm')) {
            $log = new Logger($this->config->get('logging.directory') . 'rsm2000/', LogLevel::DEBUG);
            $log->debug('Failed redirection POST=: ', $this->getParams());
        }

        $this->logRSM2000Operation($this->getParams(), "redirect-fail");

        $message = 'There was a problem.';
        $class = 'warning';
        $errors = $this->getParam('errors', null);

        $invoice_id = $this->getParam('uniqueid', null);

        if (!empty($errors) && count($errors)>0) {
            $errorCode = $errors[0]['code'];
            $errorMessage = $errors[0]['message'];
            $message = $errorMessage;
        }

        //Error: 1014 - Unique ID has been used before. /Invoice is paid?
        if (!empty($errorCode) && !empty($invoice_id)) {
            $this->invoiceStore = Store::get('Invoice');

            /** @type \Octo\Invoicing\Model\Invoice $invoice */
            $invoice = $this->invoiceStore->getById($invoice_id);

            //3001: User cancelled transaction.
            if ($errorCode >= 2000 && $errorCode < 3000) {
                $log = new Logger($this->config->get('logging.directory'), LogLevel::DEBUG);
                $log->debug('Contact validation failed for RSM2000: ', $this->request->getParams());
            }

            if ($errorCode == 1014) {
                if ($invoice && ($invoice->getTotal() <= $invoice->getTotalPaid())) {
                    //$class = 'success';
                    //$message = 'That invoice is marked as paid.';

                    //Redirect to thanks page, as it looks like the invoice is paid.
                    die('<script>top.window.location.href="/checkout/thanks/'.$invoice->getUuid().'";</script>');
                } else {
                    //UniqueId cannot be use, Invoice not paid
                    $class = 'warning';
                    $message = '<p>Payment gateway will not process this transaction anymore.</p>';
                }
            }

        }

        $message .= '<p><a href="javascript:void(0)" onclick="top.window.location.href=\'/checkout/\';">Go to Checkout</a></p>';
        echo '<div class="alert alert-'.$class.'" role="alert">'.$message.'</div>';
    }

    /**
     * Log error from RSM2000 failed payment to DB
     * @param array $post
     * @param string $type
     */
    protected function logRSM2000Operation($post, $type="callback", $securityPass=null)
    {
        $rsm2000log = new Rsm2000Log();
        $rsm2000log->setInvoiceId($this->getParam('uniqueid', null));
        $rsm2000log->setDonation($this->getParam('donation', null));
        $rsm2000log->setPurchase($this->getParam('purchase', null));
        if (!is_null($securityPass)) {
            $rsm2000log->setSecurityPass($securityPass);
        }

        if ($type == "callback") {
            $rsm2000log->setCardType($this->getParam('cardType', null));
            $rsm2000log->setTransId($this->getParam('transId', null));
            $rsm2000log->setRawAuthMessage($this->getParam('rawAuthMessage', null));
            $rsm2000log->setTransTime($this->getParam('transTime', null));
            $rsm2000log->setTransStatus($this->getParam('transStatus', null));
            $rsm2000log->setBaseStatus($this->getParam('baseStatus', null));
        } elseif ($type == "redirect-fail") {
        //Redirect
            $rsm2000log->setCardType($this->getParam('cardtype', null));
            $rsm2000log->setTransId($this->getParam('transid', null));
            $rsm2000log->setTransTime(time());
            $rsm2000log->setTransStatus('');
            $rsm2000log->setBaseStatus($this->getParam('status', null));
            $errorMessage = $this->getParam('errors')[0]['code'] . " : " . $this->getParam('errors')[0]['message'];
            $rsm2000log->setRawAuthMessage($errorMessage);
        } else {
            //Redirect success
            $rsm2000log->setCardType($this->getParam('cardtype', null));
            $rsm2000log->setTransId($this->getParam('transid', null));
            $rsm2000log->setRawAuthMessage('');
            $rsm2000log->setTransTime($this->getParam('transtime', null));
            $rsm2000log->setTransStatus('');
            $rsm2000log->setBaseStatus($this->getParam('status', null));
        }

        Store::get('Rsm2000Log')->save($rsm2000log);
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
