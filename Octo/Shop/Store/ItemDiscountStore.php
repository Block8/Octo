<?php

/**
 * ItemDiscount store for table: item_discount */

namespace Octo\Shop\Store;

use b8\Cache;
use b8\Database;
use b8\Database\Query;
use Octo;

/**
 * ItemDiscount Store
 */
class ItemDiscountStore extends Octo\Store
{
    use Base\ItemDiscountStoreBase;

    /**
     * Get all discount options assigned to item category
     * @param $categoryId
     * @throws StoreException
     */
    public function getAllForCategory($categoryId)
    {
        $query = new Query($this->getNamespace('ItemDiscount') . '\Model\ItemDiscount');
        $query->from('item_discount')->where('category_id = :categoryId');
        $query->order('price_adjustment', 'DESC');
        $query->bind(':categoryId', $categoryId);

        try {
            $query->execute();
            return $query->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            throw new StoreException('Could not get ItemDiscount by CategoryId', 0, $ex);
        }
    }

    /**
     * For a categoryId find DiscountOptions that are not defined (have not got any price_adjustment)
     * @param $categoryId
     * @return array
     */
    public function getOptionNotDefinedForCategory($categoryId)
    {
        $query = " SELeCT do.* from discount_option as do
                    WHERE do.id NOT IN (SELECT discount_option_id FROM item_discount WHERE category_id = :categoryId)
                    AND discount_id = (SELECT DISTINCT discount_id FROM item_discount WHERE category_id = :categoryId LIMIT 1)";
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindValue(':categoryId', $categoryId);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $data = [];
            $ret = [];
            foreach ($res as $item) {
                $data['amount_initial'] = $item['amount_initial'];
                $data['amount_final'] = $item['amount_final'];
                $data['item_id'] = null;
                $data['category_id'] = $categoryId;
                $data['item_id'] = null;
                $data['price_adjustment'] = '0';
                $data['discount_id'] = $item['discount_id'];
                $data['discount_option_id'] = $item['id'];
                $ret[] = $data;
            }
            return $ret;
        } else {
            return array();
        }
    }

    /**
     * Get discount price table to calculate item price
     * @param $categoryId
     * @throws StoreException
     */
    public function getDiscountTableForCategory($categoryId)
    {
        $query = new Query();
        $query->select('discount_option.amount_initial, item_discount.price_adjustment');
        $query->from('item_discount');
        $query->join('discount_option', '', 'discount_option.id = item_discount.discount_option_id');
        $query->where('item_discount.category_id = :categoryId');
        $query->bind(':categoryId', $categoryId);

        try {
            if ($query->execute()) {
                $res = $query->fetchAll(\PDO::FETCH_ASSOC);
                $data = [];
                foreach ($res as $item) {
                    $data[$item['amount_initial']] = $item['price_adjustment'];
                }
                return $data;
            } else {
                return array();
            }
        } catch (PDOException $ex) {
            throw new StoreException('Could not get DiscountTable by CategoryId', 0, $ex);
        }
    }

    /**
     * Delete assigned discounts options for categoryId
     * @param $categoryId
     * @param $discountId
     * @return int
     */
    public function deleteDiscountForCategory($categoryId, $discountId)
    {
        $stmt = Database::getConnection('write')->prepare(
            'DELETE FROM item_discount WHERE category_id = :categoryId AND discount_id = :discountId'
        );
        $stmt->bindValue(':discountId', $discountId);
        $stmt->bindValue(':categoryId', $categoryId);
        if ($stmt->execute()) {
            return $stmt->rowCount();
        } else {
            return 0;
        }
    }
}
