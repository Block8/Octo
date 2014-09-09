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
    /** @var \Octo\Invoicing\Store\LineItemStore */
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
            $uniqueSuppliers[] = array();
            foreach($items as $item) {
                if(is_numeric($item->Item->getFulfilmentHouseId())) {
                    if(!isset($uniqueSuppliers[$item->Item->getFulfilmentHouseId()])) {
                        $uniqueSuppliers[$item->Item->getFulfilmentHouseId()] = array();
                    }
                    $uniqueSuppliers[$item->Item->getFulfilmentHouseId()][] = $item;
                }
            }

            foreach($uniqueSuppliers as $supplier_id=>$items) {
                $this->instructSupplier($this->supplierStore->getByPrimaryKey($supplier_id), $items, $invoice);
            }
        }
    }

    public function instructSupplier($supplier, $items, $invoice) {
        if(!isset($supplier)) {
            return;
        }
        $itemList = "<table width='100%'><thead><tr><th>Item</th><th>Quantity</th></tr></thead>";
        foreach($items as $item) {
            $itemList.="<tr><td>".$item->getDescription()."</td><td>".$item->getQuantity()."</td>";
        }
        $itemList.="</table>";
        $shipping = json_decode($invoice->getShippingAddress(), true);
        $shippingAddress = "";
        $shippingAddress.=$shipping['address1']."<br />";
        $shippingAddress.=$shipping['address2']."<br />";
        $shippingAddress.=$shipping['town']."<br />";
        $shippingAddress.=$shipping['postcode']."<br />";
        $shippingAddress.=$shipping['country_name'];

        $customerName = $invoice->Contact->first_name." ".$invoice->Contact->first_name;

        $message = $supplier->getEmailCopy();
        $message = str_replace("{items}", $itemList, $message);
        $message = str_replace("{shipping_address}", $shippingAddress, $message);
        $message = str_replace("{invoice_number}", $invoice->getId(), $message);
        $message = str_replace("{customer_name}", $customerName, $message);

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
            $mail->SetFrom('remedia@re-systems.co.uk');
        }
        if($supplier->getEmail1()) {
            $mail->addAddress($supplier->getEmail1());
        }
        if($supplier->getEmail2()) {
            $mail->addAddress($supplier->getEmail2());
        }
        if($supplier->getEmail3()) {
            $mail->addAddress($supplier->getEmail3());
        }
        $mail->Body = $message;
        $mail->send();
    }
}
