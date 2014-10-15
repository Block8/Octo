<?php

/**
 * Item store for table: item */

namespace Octo\Invoicing\Store;

use b8\Database;
use b8\Database\Query;
use Octo;
use Octo\Invoicing\Model\Item;

/**
 * Item Store
 */
class ItemStore extends Octo\Store
{
    use Base\ItemStoreBase;

    public function getAll($activeOnly = true)
    {
        $query = new Query($this->getNamespace('Item') . '\Model\Item');
        $query->select('*')->from('item');

        if ($activeOnly) {
            $query->where('active = 1');
        }

        return $query->execute()->fetchAll();
    }

    //LPP. Should have expiry_date
    public function getByCategoryId($categoryId, $activeOnly = true)
    {
        $query = new Query($this->getNamespace('Item') . '\Model\Item');
        $query->select('*')->from('item');
        $query->where('category_id = :category_id' . ($activeOnly ? ' AND active = 1' : ''));
        $query->bind(':category_id', $categoryId);
        $query->order('title', 'ASC');

        return $query->execute()->fetchAll();
    }

    public function search($query)
    {
        $query = 'SELECT * FROM item WHERE title LIKE \'%'.$query.'%\'';

        $stmt = Database::getConnection('read')->prepare($query);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Item($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
        }
    }

    public function getModelsToIndex()
    {
        return $this->getAll();
    }


    /**
     * @param $value
     * @param $categoryId
     * @param bool $activeOnly
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return Item
     */
    public function getBySlugAndCategory($value, $categoryId, $activeOnly = true, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('Item').'\Model\Item', $useConnection);
        $query->select('*')->from('item')->limit(1);
        $query->where('`slug` = :slug AND category_id = :category_id' . ($activeOnly ? ' AND active = 1' : ''));
        $query->bind(':slug', $value);
        $query->bind(':category_id', $categoryId);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get Item by Slug', 0, $ex);
        }
    }

    /**
     * Get products list to make them an option to pick as a related to some other product
     * @param $productId
     * @param bool $activeOnly
     * @param string $useConnection
     * @return array
     * @throws StoreException
     */
    public function getProductsNotRelatedToProductId($productId, $activeOnly = true, $useConnection = 'read')
    {
        if (is_null($productId)) {
            throw new StoreException('ProductId passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('Item').'\Model\Item', $useConnection);

        $query->select('*')->from('item');
        $query->where(' ' . ($activeOnly ? ' item.active = 1 ' : ' ') .
                        ' AND item.id NOT IN ( SELECT DISTINCT item_related.related_item_id
                        FROM item_related
                        WHERE item_related.item_id = :product_id )
                        AND item.id <> :product_id
                        ORDER BY item.title'
        );

        $query->bind(':product_id', $productId);

        try {
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get Items available to be related by productId.', 0, $ex);
        }

    }

}
