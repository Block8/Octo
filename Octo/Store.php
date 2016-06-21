<?php

namespace Octo;

abstract class Store extends \Block8\Database\Store
{
    /**
     * Makes table name public
     *
     * @return mixed
     */
    public function getTableName()
    {
        return $this->table;
    }

    public function getByPrimaryKey($key)
    {
        throw new \Exception('Get by key not implemented for this store, as there is no primary key.');
    }
}
