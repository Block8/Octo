<?php

/**
 * GaTopPage base store for table: ga_top_page
 */

namespace Octo\Store\Base;

use PDOException;
use b8\Cache;
use b8\Database;
use b8\Database\Query;
use b8\Database\Query\Criteria;
use b8\Exception\StoreException;
use Octo\Store;
use Octo\Model\GaTopPage;

/**
 * GaTopPage Base Store
 */
trait GaTopPageStoreBase
{
    protected function init()
    {
        $this->tableName = 'ga_top_page';
        $this->modelName = '\Octo\Model\GaTopPage';
        $this->primaryKey = 'id';
    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return GaTopPage
    */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }


    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return GaTopPage
    */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query('Octo\Model\GaTopPage', $useConnection);
        $query->select('*')->from('ga_top_page')->limit(1);
        $query->where('`id` = :id');
        $query->bind(':id', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get GaTopPage by GaTopPage', 0, $ex);
        }
    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return GaTopPage
    */
    public function getByUri($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query('Octo\Model\GaTopPage', $useConnection);
        $query->select('*')->from('ga_top_page')->limit(1);
        $query->where('`uri` = :uri');
        $query->bind(':uri', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get GaTopPage by GaTopPage', 0, $ex);
        }
    }

    /**
     * @param $value
     * @param array $options Offsets, limits, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return int
     */
    public function getTotalForPageId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query('Octo\Model\GaTopPage', $useConnection);
        $query->from('ga_top_page')->where('`page_id` = :page_id');
        $query->bind(':page_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            return $query->getCount();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get count of GaTopPage by PageId', 0, $ex);
        }
    }

    /**
     * @param $value
     * @param array $options Limits, offsets, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return GaTopPage[]
     */
    public function getByPageId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query('Octo\Model\GaTopPage', $useConnection);
        $query->from('ga_top_page')->where('`page_id` = :page_id');
        $query->bind(':page_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get GaTopPage by PageId', 0, $ex);
        }

    }
}
