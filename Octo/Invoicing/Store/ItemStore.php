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
}
