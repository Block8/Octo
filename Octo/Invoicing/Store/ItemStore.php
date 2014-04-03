<?php

/**
 * Item store for table: item */

namespace Octo\Invoicing\Store;

use b8\Database;
use b8\Database\Query;
use Octo;

/**
 * Item Store
 */
class ItemStore extends Octo\Store
{
    use Base\ItemStoreBase;

    public function getAll()
    {
        $query = new Query($this->getNamespace('Item') . '\Model\Item');
        $query->select('*')->from('item');
        $query->where('active = 1');

        return $query->execute()->fetchAll();
    }

    public function getByCategoryId($categoryId)
    {
        $query = new Query($this->getNamespace('Item') . '\Model\Item');
        $query->select('*')->from('item');
        $query->where('category_id = :category_id AND active = 1');
        $query->bind(':category_id', $categoryId);

        return $query->execute()->fetchAll();
    }
}
