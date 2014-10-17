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

    public function getCountItemsInCategory($basketId, $categoryId)
    {
        $query = new Query();
        $query->select('SUM(quantity) as categoryQuantity');
        $query->from('line_item');
        $query->join('item', '', 'item.id = line_item.item_id');
        $query->where('`basket_id` = :basket_id AND item.category_id = :categoryId');
        $query->limit(1);
        $query->bind(':basket_id', $basketId);
        $query->bind(':categoryId', $categoryId);

        try {
            $query->execute();
            $ret = $query->fetch();
            return $ret['categoryQuantity'];
        } catch (PDOException $ex) {
            throw new StoreException('Could not get sum for item category from Basket', 0, $ex);
        }
    }

    public function getLineItemInCategoryFromBasket($basketId, $categoryId)
    {
        if (is_null($basketId) || is_null($categoryId)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }
        $query = 'SELECT line_item.* FROM line_item, item WHERE `basket_id` = :basket_id AND item.category_id = :categoryId AND item.id = line_item.item_id';
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindValue(':basket_id', $basketId);
        $stmt->bindValue(':categoryId', $categoryId);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Octo\Invoicing\Model\LineItem($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return array();
        }
        /*
        $query = new Query($this->getNamespace('LineItem').'\Model\LineItem');
        $query->from('line_item');
        $query->join('item', '', 'item.id = line_item.item_id');
        $query->where('`basket_id` = :basket_id AND item.category_id = :categoryId');
        $query->bind(':basket_id', $basketId);
        $query->bind(':categoryId', $categoryId);

        try {
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get LineItems for Category from Basket', 0, $ex);
        }
        */
    }


}
