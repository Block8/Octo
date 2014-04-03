<?php

/**
 * Menu base store for table: menu
 */

namespace Octo\Menu\Store\Base;

use PDOException;
use b8\Cache;
use b8\Database;
use b8\Database\Query;
use b8\Database\Query\Criteria;
use b8\Exception\StoreException;
use Octo\Store;
use Octo\Menu\Model\Menu;

/**
 * Menu Base Store
 */
trait MenuStoreBase
{
    protected function init()
    {
        $this->tableName = 'menu';
        $this->modelName = '\Octo\Menu\Model\Menu';
        $this->primaryKey = 'id';
    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return Menu
    */
    public function getByPrimaryKey($value, $useConnection = 'read')
    {
        return $this->getById($value, $useConnection);
    }


    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return Menu
    */
    public function getById($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('Menu').'\Model\Menu', $useConnection);
        $query->select('*')->from('menu')->limit(1);
        $query->where('`id` = :id');
        $query->bind(':id', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get Menu by Id', 0, $ex);
        }
    }
    /**
    * @param $value
    * @param string $useConnection Connection type to use.
    * @throws StoreException
    * @return Menu
    */
    public function getByTemplateTag($value, $useConnection = 'read')
    {
        if (is_null($value)) {
            throw new StoreException('Value passed to ' . __FUNCTION__ . ' cannot be null.');
        }

        $query = new Query($this->getNamespace('Menu').'\Model\Menu', $useConnection);
        $query->select('*')->from('menu')->limit(1);
        $query->where('`template_tag` = :template_tag');
        $query->bind(':template_tag', $value);

        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $ex) {
            throw new StoreException('Could not get Menu by TemplateTag', 0, $ex);
        }
    }
}
