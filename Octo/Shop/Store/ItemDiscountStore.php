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

    // This class has been left blank so that you can modify it - changes in this file will not be overwritten.

    public function getAllForCategory($categoryId)
    {
        $query = new Query($this->getNamespace('ItemDiscount') . '\Model\ItemDiscount');
        $query->from('item_discount')->where('category_id = :categoryId');
        $query->bind(':categoryId', $categoryId);

        try {
            $query->execute();
            return $query->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            throw new StoreException('Could not get ItemDiscount by CategoryId', 0, $ex);
        }
    }

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
