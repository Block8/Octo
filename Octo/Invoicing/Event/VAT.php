<?php
namespace Octo\Invoicing\Event;

use Octo\Event\Listener;
use Octo\Event\Manager;
use Octo\Invoicing\Model\Invoice;
use Octo\Invoicing\Model\InvoiceAdjustment;
use Octo\Store;

class VAT extends Listener
{
    /**
     * @var \Octo\Invoicing\Store\InvoiceAdjustmentStore
     */
    protected $adjustmentStore;

    public function __construct()
    {
        $this->adjustmentStore = Store::get('InvoiceAdjustment');
    }

    public function registerListeners(Manager $manager)
    {
        $manager->registerListener('InvoiceItemsUpdated', array($this, 'addVatAdjustment'));
    }

    public function addVatAdjustment(Invoice &$invoice)
    {
        $subtotal = $invoice->getSubtotal();
        $vat = ($subtotal / 100) * 20;

        $adjustment = new InvoiceAdjustment();
        $adjustment->setInvoice($invoice);
        $adjustment->setScope('vat');
        $adjustment->setTitle('VAT');
        $adjustment->setValue($vat);

        $this->adjustmentStore->saveByReplace($adjustment);
    }
}
