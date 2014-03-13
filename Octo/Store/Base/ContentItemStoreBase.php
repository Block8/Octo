<?php

/**
 * ContentItem base store for table: content_item
 */

namespace Octo\Store\Base;

use PDOException;
use b8\Database;
use b8\Database\Query;
use b8\Database\Query\Criteria;
use b8\Exception\StoreException;
use Octo\Store;
use Octo\Model\ContentItem;

/**
 * ContentItem Base Store
 */
class ContentItemStoreBase extends Store
{
    protected $tableName   = 'content_item';
    protected $modelName   = '\Octo\Model\ContentItem';
    protected $primaryKey  = 'id';

    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return ContentItem
    */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }


    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return ContentItem
    */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query('Octo\Model\ContentItem', $useConnection);
        $query->select('*')->from('content_item')->limit(1);
        $query->where('`id` = :id');
        $query->bind(':id', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get ContentItem by ContentItem', 0, $ex);
        }
    }
}
