<?php

namespace Octo\Invoicing\Service;

use DateTime;
use Exception;
use Octo\Event;
use Octo\Invoicing\Model\Invoice;
use Octo\Invoicing\Model\LineItem;
use Octo\Invoicing\Store\InvoiceStore;
use Octo\Invoicing\Store\InvoiceAdjustmentStore;
use Octo\Invoicing\Store\ItemStore;
use Octo\Invoicing\Store\LineItemStore;
use Octo\System\Model\Contact;

class InvoiceService
{
    /**
     * @var \Octo\Invoicing\Store\InvoiceStore
     */
    protected $invoiceStore;

    /**
     * @var \Octo\Invoicing\Store\InvoiceAdjustmentStore
     */
    protected $adjustmentStore;

    /**
     * @var \Octo\Invoicing\Store\LineItemStore
     */
    protected $lineItemStore;

    /**
     * @var \Octo\Invoicing\Store\ItemStore
     */
    protected $itemStore;

    /**
     * @param InvoiceStore $invoiceStore
     * @param InvoiceAdjustmentStore $adjustmentStore
     * @param ItemStore $itemStore
     * @param LineItemStore $lineItemStore
     */
    public function __construct(InvoiceStore $invoiceStore, InvoiceAdjustmentStore $adjustmentStore, ItemStore $itemStore, LineItemStore $lineItemStore)
    {
        $this->invoiceStore = $invoiceStore;
        $this->adjustmentStore = $adjustmentStore;
        $this->lineItemStore = $lineItemStore;
        $this->itemStore = $itemStore;
    }

    /**
     * @param Contact $contact
     * @param DateTime $invoiceDate
     * @return Invoice
     */
    public function createInvoice(Contact $contact, DateTime $invoiceDate)
    {
        // Create the invoice:
        $invoice = new Invoice();
        $invoice->setCreatedDate($invoiceDate);
        $invoice->setUpdatedDate(new DateTime());
        $invoice->setContact($contact);
        $invoice->setInvoiceStatusId(Invoice::STATUS_NEW);

        // Save the invoice and return the updated model (incl. ID)
        $invoice = $this->invoiceStore->saveByInsert($invoice);

        return $invoice;
    }

    public function updateInvoiceItems(Invoice $invoice, array $itemDetails)
    {
        if (!$invoice->getId()) {
            throw new Exception('Error updating invoice items: You must save the invoice before updating its items.');
        }

        if ($invoice->getInvoiceStatusId() != Invoice::STATUS_NEW) {
            throw new Exception('Error updating invoice items: You cannot edit invoice items after the invoice has been sent.');
        }

        $this->lineItemStore->clearItemsForInvoice($invoice);

        $invoiceSubTotal = 0;
        foreach ($itemDetails as $item) {
            if (empty($item['item_id'])) {
                throw new Exception('Error updating invoice items: Missing item ID.');
            }

            if (empty($item['description'])) {
                $item['description'] = '';
            }

            if (empty($item['quantity'])) {
                $item['quantity'] = 1;
            }

            $itemObject = $this->itemStore->getById($item['item_id']);

            if (is_null($itemObject)) {
                throw new Exception('Error updating invoice items: Invalid item ID ('.$item['item_id'].').');
            }

            $lineItem = new LineItem();
            $lineItem->setInvoice($invoice);
            $lineItem->setItem($itemObject);
            $lineItem->setDescription($item['description']);
            $lineItem->setQuantity($item['quantity']);
            $lineItem->setItemPrice($itemObject->getPrice());

            if (!empty($item['item_price'])) {
                $lineItem->setItemPrice($item['item_price']);
            }

            $lineItem->setLinePrice($lineItem->getItemPrice() * $lineItem->getQuantity());
            $invoiceSubTotal += $lineItem->getLinePrice();

            $this->lineItemStore->save($lineItem);
        }

        $invoice->setSubtotal($invoiceSubTotal);
        Event::trigger('InvoiceItemsUpdated', $invoice);
        $this->updateInvoiceTotal($invoice);

        if (Event::trigger('BeforeInvoiceSave', $invoice)) {
            $invoice = $this->invoiceStore->save($invoice);
        }

        Event::trigger('OnInvoiceSave', $invoice);

        return $invoice;
    }

    public function updateInvoiceTotal(Invoice &$invoice)
    {
        $total = $invoice->getSubtotal();
        $adjustments = $this->adjustmentStore->getByInvoiceId($invoice->getId());

        foreach ($adjustments as $adjustment) {
            $total += $adjustment->getValue();
        }

        $invoice->setTotal($total);
    }
}