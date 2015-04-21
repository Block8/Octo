<?php

/**
 * SearchIndex base store for table: search_index
 */

namespace Octo\System\Store\Base;

use PDOException;
use b8\Cache;
use b8\Database;
use b8\Database\Query;
use b8\Database\Query\Criteria;
use b8\Exception\StoreException;
use Octo\Store;
use Octo\System\Model\SearchIndex;
use Octo\System\Model\SearchIndexCollection;

/**
 * SearchIndex Base Store
 */
trait SearchIndexStoreBase
{
    protected function init()
    {
        $this->tableName = 'search_index';
        $this->modelName = '\Octo\System\Model\SearchIndex';
        $this->primaryKey = 'id';
    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return SearchIndex
    */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }


    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return SearchIndex
    */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }
        // This is the primary key, so try and get from cache:
        $cacheResult = $this->getFromCache($value);

        if (!empty($cacheResult)) {
            return $cacheResult;
        }


        $query = new Query($this->getNamespace('SearchIndex').'\Model\SearchIndex', $useConnection);
        $query->select('*')->from('search_index')->limit(1);
        $query->where('`id` = :id');
        $query->bind(':id', $value);

        try {
            $query->execute();
            $result = $query->fetch();

            $this->setCache($value, $result);

            return $result;
        } catch (PDOException $ex) {
            throw new StoreException('Could not get SearchIndex by Id', 0, $ex);
        }
    }

    /**
     * @param $value
     * @param array $options Offsets, limits, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return int
     */
    public function getTotalForWord($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('SearchIndex').'\Model\SearchIndex', $useConnection);
        $query->from('search_index')->where('`word` = :word');
        $query->bind(':word', $value);

        $this->handleQueryOptions($query, $options);

        try {
            return $query->getCount();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get count of SearchIndex by Word', 0, $ex);
        }
    }

    /**
     * @param $value
     * @param array $options Limits, offsets, etc.
     * @param string $useConnection Connection type to use.
     * @throws StoreException
     * @return SearchIndex[]
     */
    public function getByWord($value, $options = [], $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('SearchIndex').'\Model\SearchIndex', $useConnection);
        $query->from('search_index')->where('`word` = :word');
        $query->bind(':word', $value);

        $this->handleQueryOptions($query, $options);

        try {
            $query->execute();
            return new SearchIndexCollection($query->fetchAll());
        } catch (PDOException $ex) {
            throw new StoreException('Could not get SearchIndex by Word', 0, $ex);
        }

    }
}
