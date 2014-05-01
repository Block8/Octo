<?php

/**
 * ShopBasket base store for table: shop_basket
 */

namespace Octo\Shop\Store\Base;

use PDOException;
use b8\Cache;
use b8\Database;
use b8\Database\Query;
use b8\Database\Query\Criteria;
use b8\Exception\StoreException;
use Octo\Store;
use Octo\Shop\Model\ShopBasket;

/**
 * ShopBasket Base Store
 */
trait ShopBasketStoreBase
{
    protected function init()
    {
        $this->tableName = 'shop_basket';
        $this->modelName = '\Octo\Shop\Model\ShopBasket';
        $this->primaryKey = 'id';
    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return ShopBasket
    */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }


    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return ShopBasket
    */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('ShopBasket').'\Model\ShopBasket', $useConnection);
        $query->select('*')->from('shop_basket')->limit(1);
        $query->where('`id` = :id');
        $query->bind(':id', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get ShopBasket by Id', 0, $ex);
        }
    }
}
