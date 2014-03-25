<?php

/**
 * ItemFile base store for table: item_file
 */

namespace Octo\Shop\Store\Base;

use PDOException;
use b8\Cache;
use b8\Database;
use b8\Database\Query;
use b8\Database\Query\Criteria;
use b8\Exception\StoreException;
use Octo\Shop\Store;
use Octo\Shop\Model\ItemFile;

/**
 * ItemFile Base Store
 */
trait ItemFileStoreBase
{
    protected function init()
    {
        $this->tableName = 'item_file';
        $this->modelName = '\Octo\Shop\Model\ItemFile';
        $this->primaryKey = 'id';
    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return ItemFile
    */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }


    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return ItemFile
    */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('ItemFile').'\Model\ItemFile', $useConnection);
        $query->select('*')->from('item_file')->limit(1);
        $query->where('`id` = :id');
        $query->bind(':id', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get ItemFile by Id', 0, $ex);
        }
    }

    /**
     * @param $value
     * @param array $options Offsets, limits, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return int
     */
    public function getTotalForItemId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('ItemFile').'\Model\ItemFile', $useConnection);
        $query->from('item_file')->where('`item_id` = :item_id');
        $query->bind(':item_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            return $query->getCount();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get count of ItemFile by ItemId', 0, $ex);
        }
    }

    /**
     * @param $value
     * @param array $options Limits, offsets, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return ItemFile[]
     */
    public function getByItemId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('ItemFile').'\Model\ItemFile', $useConnection);
        $query->from('item_file')->where('`item_id` = :item_id');
        $query->bind(':item_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get ItemFile by ItemId', 0, $ex);
        }

    }

    /**
     * @param $value
     * @param array $options Offsets, limits, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return int
     */
    public function getTotalForFileId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('ItemFile').'\Model\ItemFile', $useConnection);
        $query->from('item_file')->where('`file_id` = :file_id');
        $query->bind(':file_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            return $query->getCount();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get count of ItemFile by FileId', 0, $ex);
        }
    }

    /**
     * @param $value
     * @param array $options Limits, offsets, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return ItemFile[]
     */
    public function getByFileId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('ItemFile').'\Model\ItemFile', $useConnection);
        $query->from('item_file')->where('`file_id` = :file_id');
        $query->bind(':file_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get ItemFile by FileId', 0, $ex);
        }

    }
}
