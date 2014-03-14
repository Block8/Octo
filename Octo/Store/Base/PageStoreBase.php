<?php

/**
 * Page base store for table: page
 */

namespace Octo\Store\Base;

use PDOException;
use b8\Cache;
use b8\Database;
use b8\Database\Query;
use b8\Database\Query\Criteria;
use b8\Exception\StoreException;
use Octo\Store;
use Octo\Model\Page;

/**
 * Page Base Store
 */
trait PageStoreBase
{
    protected function init()
    {
        $this->tableName = 'page';
        $this->modelName = '\Octo\Model\Page';
        $this->primaryKey = 'id';
    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return Page
    */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }


    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return Page
    */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query('Octo\Model\Page', $useConnection);
        $query->select('*')->from('page')->limit(1);
        $query->where('`id` = :id');
        $query->bind(':id', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get Page by Id', 0, $ex);
        }
    }

    /**
     * @param $value
     * @param array $options Offsets, limits, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return int
     */
    public function getTotalForParentId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query('Octo\Model\Page', $useConnection);
        $query->from('page')->where('`parent_id` = :parent_id');
        $query->bind(':parent_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            return $query->getCount();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get count of Page by ParentId', 0, $ex);
        }
    }

    /**
     * @param $value
     * @param array $options Limits, offsets, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return Page[]
     */
    public function getByParentId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query('Octo\Model\Page', $useConnection);
        $query->from('page')->where('`parent_id` = :parent_id');
        $query->bind(':parent_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get Page by ParentId', 0, $ex);
        }

    }

    /**
     * @param $value
     * @param array $options Offsets, limits, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return int
     */
    public function getTotalForCurrentVersionId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query('Octo\Model\Page', $useConnection);
        $query->from('page')->where('`current_version_id` = :current_version_id');
        $query->bind(':current_version_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            return $query->getCount();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get count of Page by CurrentVersionId', 0, $ex);
        }
    }

    /**
     * @param $value
     * @param array $options Limits, offsets, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return Page[]
     */
    public function getByCurrentVersionId($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query('Octo\Model\Page', $useConnection);
        $query->from('page')->where('`current_version_id` = :current_version_id');
        $query->bind(':current_version_id', $value);

        $this->handleQueryOptions($query, $options);

        try {
            $query->execute();
            return $query->fetchAll();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get Page by CurrentVersionId', 0, $ex);
        }

    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return Page
    */
    public function getByUri($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query('Octo\Model\Page', $useConnection);
        $query->select('*')->from('page')->limit(1);
        $query->where('`uri` = :uri');
        $query->bind(':uri', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get Page by Uri', 0, $ex);
        }
    }
}
