<?php

/**
 * LineItem store for table: line_item */

namespace Octo\Invoicing\Store;

use b8\Database;
use b8\Database\Query;
use Octo;
use Octo\Event;
use Octo\Invoicing\Model\Invoice;

/**
 * LineItem Store
 */
class LineItemStore extends Octo\Store
{
    use Base\LineItemStoreBase;

    public function clearItemsForInvoice($invoice)
    {
        $query = 'DELETE FROM line_item WHERE invoice_id = :invoice_id';
        $stmt = Database::getConnection('write')->prepare($query);
        $stmt->bindValue(':invoice_id', $invoice->getId());

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $value
     * @param array $options Limits, offsets, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return LineItem[]
     */
    public function getByBasketId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('LineItem').'\Model\LineItem', $useConnection);
        $query->from('line_item')->where('`basket_id` = :basket_id');
        $query->bind(':basket_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get LineItem by BasketId', 0, $ex);
        }

    }

    /*
    public function copyBasketToInvoice(\Octo\Shop\Model\ShopBasket $basket, Invoice $invoice)
    {
        $query = 'UPDATE line_item SET basket_id = NULL, invoice_id = :invoice_id WHERE basket_id = :basket_id';
        $stmt = Database::getConnection('write')->prepare($query);
        $stmt->bindValue(':invoice_id', $invoice->getId());
        $stmt->bindValue(':basket_id', $basket->getId());
        $stmt->execute();

        Event::trigger('InvoiceItemsUpdated', $invoice);

        $query = 'DELETE FROM shop_basket WHERE id = :basket_id';
        $stmt = Database::getConnection('write')->prepare($query);
        $stmt->bindValue(':basket_id', $basket->getId());
        $stmt->execute();

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    */

    public function copyBasketToInvoice(\Octo\Shop\Model\ShopBasket $basket, Invoice $invoice)
    {
        $query = 'UPDATE line_item SET invoice_id = :invoice_id WHERE basket_id = :basket_id';
        $stmt = Database::getConnection('write')->prepare($query);
        $stmt->bindValue(':invoice_id', $invoice->getId());
        $stmt->bindValue(':basket_id', $basket->getId());

        if ($stmt->execute()) {
            Event::trigger('InvoiceItemsUpdated', $invoice);
            return true;
        } else {
            return false;
        }
    }

    public function emptyShopBasket(\Octo\Shop\Model\ShopBasket $basket) {
        $query = 'UPDATE line_item SET basket_id = NULL WHERE basket_id = :basket_id';
        $stmt = Database::getConnection('write')->prepare($query);
        $stmt->bindValue(':basket_id', $basket->getId());
        $stmt->execute();

        $query = 'DELETE FROM shop_basket WHERE id = :basket_id';
        $stmt = Database::getConnection('write')->prepare($query);
        $stmt->bindValue(':basket_id', $basket->getId());
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    //LPP
    /**
     * @param $basketId
     * @param $itemId
     * @param $itemDescription
     * @param array $options
     * @param string $useConnection
     * @return Octo\Invoicing\Model\LineItem
     * @throws StoreException
     */
    public function getLineItemFromBasket($basketId, $itemId, $itemDescription, $options = [], $useConnection = 'read')
    {
        if (is_null($basketId) || is_null($itemId) || is_null($itemDescription)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('LineItem').'\Model\LineItem', $useConnection);
        $query->from('line_item')->where('`basket_id` = :basket_id AND `description` = :description AND `item_id` = :item_id');
        $query->limit(1);
        $query->bind(':basket_id', $basketId);
        $query->bind(':item_id', $itemId);
        $query->bind(':description', $itemDescription);

        $this->handleQueryOptions($query, $options);

        try {
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get LineItem from Basket', 0, $ex);
        }

    }

}
