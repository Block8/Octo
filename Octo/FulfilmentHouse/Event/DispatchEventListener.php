<?php
namespace Octo\FulfilmentHouse\Event;

use b8\Config;
use b8\Form;
use Octo\Event\Listener;
use Octo\Event\Manager;
use Octo\Invoicing\Model\Invoice;
use Octo\Shop\Model\ItemFile;
use Octo\Store;
use Octo\Template;

class DispatchEventListener extends Listener
{
    protected $lineItemStore;
    protected $supplierStore;

    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('OnInvoiceStatusChanged', array($this, 'evaluateInvoiceChange'));
    }

    public function evaluateInvoiceChange($invoice)
    {
        $this->lineItemStore = Store::get('LineItem');
        $this->supplierStore = Store::get('FulfilmentHouse');

        if($invoice->getInvoiceStatusId() == Invoice::STATUS_PAID) {
            $items = $this->lineItemStore->getByInvoiceId($invoice->getId());
            $uniqueSuppliers = array();
            foreach($items as $item) {
                echo $item->description." ";
                if(isset($item->fulfilment_house_id) && is_numeric($item->fulfilment_house_id)) {
                    if(!is_array($uniqueSuppliers[$item->fulfilment_house_id])) {
                        $uniqueSuppliers[$item->fulfilment_house_id] = array();
                    }
                    $uniqueSuppliers[$item->fulfilment_house_id][] = $item;
                }
            }

            foreach($uniqueSuppliers as $supplier_id=>$items) {
                $this->instructSupplier($this->supplierStore->getByPrimaryKey($supplier_id), $items, $invoice);
            }
        }
    }

    public function instructSupplier($supplier, $items, $invoice) {
        $itemList = "<table><thead><tr><th>Item Ordered</th><th>Quantity Ordered</th></tr></thead>";
        foreach($items as $item) {
            $itemList.="<tr><td>".$item->description."</td><td>".$item->quantity."</td>";
        }
        $itemList.="</table>";
        $shippingAddress = $invoice->shipping_address;
        $message = $supplier->emailCopy;
        $message = str_replace("{items}", $itemList, $message);
        $message = str_replace("{shipping_address}", $shippingAddress, $message);
        $config = Config::getInstance();
        $mail = new \PHPMailer();
        $mail->IsHTML(true);
        $mail->CharSet = "UTF-8";
        $mail->Subject = "New order from ".$config->site['name'];
        if(isset($config->site['smtp_server'])) {
            $mail->IsSMTP();
            $mail->Host = $config->site['smtp_server'];
        }
        if (isset($config->site['email_from'])) {
            $mail->SetFrom($config->site['email_from']);
        }
        else {
            $mail->SetFrom('octoshop@block8.net');
        }
        if(isset($supplier->email_1)) {
            $mail->addAddress($supplier->email_1);
        }
        if(isset($supplier->email_2)) {
            $mail->addAddress($supplier->email_2);
        }
        if(isset($supplier->email_3)) {
            $mail->addAddress($supplier->email_3);
        }
        $mail->Body = $message;
        $mail->send();
    }
}
