<?php

/**
 * Discount store for table: discount */

namespace Octo\Shop\Store;

use b8\Database\Query;
use Octo;

/**
 * Discount Store
 */
class DiscountStore extends Octo\Store
{
    use Base\DiscountStoreBase;

    // This class has been left blank so that you can modify it - changes in this file will not be overwritten.

    public function getAll()
    {
        $query = new Query($this->getNamespace('Discount') . '\Model\Discount');
        $query->select('*')->from('discount');

        return $query->execute()->fetchAll();
    }

    public function getDiscountsNotUsedByCategory($categoryId)
    {
        $query = new Query($this->getNamespace('Discount') . '\Model\Discount');
        $query->select('*')->from('discount');
        $query->where(
            'id NOT IN ( SELECT DISTINCT discount.id
                            FROM discount
                            LEFT JOIN item_discount ON discount.id = item_discount.discount_id
                            WHERE item_discount.category_id = :category_id
                       )'
        );
        $query->bind(':category_id', $categoryId);

        return $query->execute()->fetchAll();
    }

}
