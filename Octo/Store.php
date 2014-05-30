<?php

namespace Octo;

use b8\Database;
use b8\Config;
use b8\Store\Factory;

abstract class Store extends \b8\Store
{
    /**
     * @param string $store Name of the store you want to load.
     * @return \b8\Store
     */
    public static function get($store)
    {
        $namespace = self::getModelNamespace($store);

        if (!is_null($namespace)) {
            return Factory::getStore($store, $namespace);
        }

        return null;
    }

    public static function getModelNamespace($model)
    {
        $config = Config::getInstance();
        return $config->get('app.namespaces.'.$model, null);
    }

    protected function getNamespace($model)
    {
        return self::getModelNamespace($model);
    }

    /**
     * Makes table name public
     *
     * @return mixed
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * REPLACE INTO
     *
     * @param Model $obj
     * @param bool $saveAllColumns
     * @return null
     */
    public function saveByReplace(Model $obj, $saveAllColumns = false)
    {
        $rtn = null;
        $data = $obj->getDataArray();
        $modified = ($saveAllColumns) ? array_keys($data) : $obj->getModified();

        $cols = array();
        $values = array();
        $qParams = array();
        foreach ($modified as $key) {
            $cols[] = $key;
            $values[] = ':' . $key;
            $qParams[':' . $key] = $data[$key];
        }

        if (count($cols)) {
            $colString = implode(', ', $cols);
            $valString = implode(', ', $values);

            $queryString = 'REPLACE INTO ' . $this->tableName . ' (' . $colString . ') VALUES (' . $valString . ')';
            $query = Database::getConnection('write')->prepare($queryString);

            if ($query->execute($qParams)) {
                $newId = !empty($data[$this->primaryKey]) ? $data[$this->primaryKey] : Database::getConnection(
                    'write'
                )->lastInsertId();
                $rtn = $this->getByPrimaryKey($newId, 'write');
            }
        }

        return $rtn;
    }

    /**
     * @param $page
     * @param $perPage
     * @param array $order
     * @param array $criteria
     * @param array $bind
     * @return Database\Query
     */
    public function query($page, $perPage, array $order = [], array $criteria = [], array $bind = [])
    {
        $query = new Database\Query($this->modelName, 'read');
        $query->select('*')->from($this->tableName);

        $page -= 1; // Make the pagination zero-indexed
        $offset = $page * $perPage;
        $query->offset($offset);
        $query->limit($perPage);

        // Handle WHERE criteria:
        if (count($criteria)) {
            $criteriaContainer = new Database\Query\Criteria();

            foreach ($criteria as $where) {
                if ($where instanceof Database\Query\Criteria) {
                    $criteriaContainer->add($where);
                } else {
                    $thisCriteria = new Database\Query\Criteria();
                    $thisCriteria->where($where);

                    $criteriaContainer->add($thisCriteria);
                }
            }

            $query->where($criteriaContainer);
        }

        // Handle ORDER BY:
        if (count($order)) {
            $query->order($order[0], $order[1]);
        }

        // Handle bound parameters:
        if (count($bind)) {
            $query->bindAll($bind);
        }

        return $query;
    }
}
