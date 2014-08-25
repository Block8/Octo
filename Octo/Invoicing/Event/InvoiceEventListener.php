<?php
namespace Octo\Invoicing\Event;

use b8\Config;
use b8\Form;
use HMUK\Ecard\Model\Ecard;
use Octo\Event\Listener;
use Octo\Event\Manager;
use Octo\Invoicing\Model\Invoice;
use Octo\Shop\Model\ItemFile;
use Octo\Store;
use Octo\Template;

class InvoiceEventListener extends Listener
{
    protected $lineItemStore;

    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('OnInvoiceStatusChanged', array($this, 'evaluateInvoiceChange'));
    }

    public function evaluateInvoiceChange($invoice)
    {
        $this->lineItemStore = Store::get('LineItem');
        $config = Config::getInstance();

        $mail = new \PHPMailer();
        $mail->IsHTML(true);
        $mail->CharSet = "UTF-8";
        switch($invoice->getInvoiceStatusId()) {
            case Invoice::STATUS_PAID:
                $message = Template::getPublicTemplate('Emails/InvoiceReceipt');
                $mail->Subject = "Receipt for your purchase from ".$config->site['name'];
                break;
            case Invoice::STATUS_REFUNDED:
                $message = Template::getPublicTemplate('Emails/InvoiceRefunded');
                $mail->Subject = "Refund Confirmation from ".$config->site['name'];
                break;
        }

        if(isset($message)) {
            if(isset($config->site['smtp_server'])) {
                $mail->IsSMTP();
                $mail->Host = $config->site['smtp_server'];
            }
            $items = $this->lineItemStore->getByInvoiceId($invoice->getId());
            $message->invoice = $invoice;
            $message->items = $items;
            if (isset($config->site['email_from'])) {
                $mail->SetFrom($config->site['email_from']);
            }
            else {
                $mail->SetFrom('octoshop@block8.net');
            }
            $mail->addAddress($invoice->Contact->email);
            $mail->Body = $message->render();
            $mail->send();
        }
    }
}
