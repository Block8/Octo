<?php

namespace Octo\Invoicing\Service;

use DateTime;
use Exception;
use Octo\Event;
use Octo\Invoicing\Model\Invoice;
use Octo\Invoicing\Model\InvoiceStatus;
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
    public function __construct(
        InvoiceStore $invoiceStore,
        InvoiceAdjustmentStore $adjustmentStore,
        ItemStore $itemStore,
        LineItemStore $lineItemStore
    )
    {
        $this->invoiceStore = $invoiceStore;
        $this->adjustmentStore = $adjustmentStore;
        $this->lineItemStore = $lineItemStore;
        $this->itemStore = $itemStore;
    }

    public function createInvoice($title, Contact $contact, DateTime $invoiceDate, $dueDate)
    {
        if (!is_null($dueDate) && !($dueDate instanceof DateTime)) {
            throw new Exception('Due Date must be either NULL or a DateTime object.');
        }

        // Create the invoice:
        $invoice = new Invoice();
        $invoice->setTitle($title);
        $invoice->setCreatedDate($invoiceDate);
        $invoice->setDueDate($dueDate);
        $invoice->setUpdatedDate(new DateTime());
        $invoice->setContact($contact);
        $invoice->setInvoiceStatusId(Invoice::STATUS_NEW);

        // Save the invoice and return the updated model (incl. ID)
        if (Event::trigger('BeforeInvoiceCreate', $invoice)) {
            $invoice = $this->invoiceStore->saveByInsert($invoice);
        }
        Event::trigger('OnInvoiceCreate', $invoice);

        return $invoice;
    }

    public function updateInvoice(Invoice $invoice, $title, Contact $contact, DateTime $invoiceDate, $dueDate)
    {
        if (!is_null($dueDate) && !($dueDate instanceof DateTime)) {
            throw new Exception('Due Date must be either NULL or a DateTime object.');
        }

        $invoice->setTitle($title);
        $invoice->setCreatedDate($invoiceDate);
        $invoice->setDueDate($dueDate);
        $invoice->setUpdatedDate(new DateTime());
        $invoice->setContact($contact);

        if (Event::trigger('BeforeInvoiceSave', $invoice)) {
            $invoice = $this->invoiceStore->saveByUpdate($invoice);
        }
        Event::trigger('OnInvoiceSave', $invoice);

        return $invoice;
    }

    public function updateInvoiceStatus(Invoice $invoice, InvoiceStatus $status)
    {
        $invoice->setInvoiceStatus($status);
        $invoice = $this->invoiceStore->saveByUpdate($invoice);
        Event::trigger('OnInvoiceStatusChanged', $invoice);

        return $invoice;
    }

    public function updateInvoiceItems(Invoice $invoice, array $itemDetails)
    {
        if (!$invoice->getId()) {
            throw new Exception('Error updating invoice items: You must save the invoice before updating its items.');
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

        return $invoice;
    }

    public function updateSubtotal(Invoice &$invoice)
    {
        $items = $this->lineItemStore->getByInvoiceId($invoice->getId());
        $invoiceSubTotal = 0;

        foreach ($items as $item) {
            $invoiceSubTotal += $item->getLinePrice();
        }

        $invoice->setSubtotal($invoiceSubTotal);
        $this->updateInvoiceTotal($invoice);

        if (Event::trigger('BeforeInvoiceSave', $invoice)) {
            $invoice = $this->invoiceStore->save($invoice);
        }

        Event::trigger('OnInvoiceSave', $invoice);

        return $invoice;
    }
}
