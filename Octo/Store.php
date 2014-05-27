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

    public function getPaginatedResults($modelName, $table, $page, $perPage, array $criteria)
    {
        $query = new Database\Query($modelName, 'read');
        $query->select('*')->from($table);

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

        // Make the pagination zero-indexed:
        $page -= 1;
        $offset = ($page - 1) * $perPage;

        $query->offset($offset);
        $query->limit($perPage);

        $query->where($criteriaContainer);

        $query->execute();
        return $query->fetchAll();
    }
}
