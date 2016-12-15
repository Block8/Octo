<?php

namespace Octo;

use Block8\Database;

abstract class Store extends Database\Store
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

    public function insert(Database\Model $model)
    {
        $model = parent::insert($model);
        Event::trigger('Octo.Model.Created', $model);
        Event::trigger('Octo.Model.Updated', $model);

        return $model;
    }

    public function replace(Database\Model $model)
    {
        $model = parent::replace($model);
        Event::trigger('Octo.Model.Updated', $model);

        return $model;
    }

    public function update(Database\Model $model)
    {
        $model = parent::update($model);
        Event::trigger('Octo.Model.Updated', $model);

        return $model;
    }

    public function delete(Database\Model $model)
    {
        Event::trigger('Octo.Model.Deleted', $model);
        return parent::delete($model);
    }
}
