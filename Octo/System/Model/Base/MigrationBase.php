<?php

/**
 * Migration base model for table: migration
 */

namespace Octo\System\Model\Base;

use b8\Store\Factory;

/**
 * Migration Base Model
 */
trait MigrationBase
{
    protected function init()
    {
        $this->tableName = 'migration';
        $this->modelName = 'Migration';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['date_run'] = null;
        $this->getters['date_run'] = 'getDateRun';
        $this->setters['date_run'] = 'setDateRun';

        // Foreign keys:
    }
    /**
    * Get the value of Id / id.
    *
    * @return string
    */
    public function getId()
    {
        $rtn = $this->data['id'];

        return $rtn;
    }

    /**
    * Get the value of DateRun / date_run.
    *
    * @return \DateTime
    */
    public function getDateRun()
    {
        $rtn = $this->data['date_run'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

        return $rtn;
    }


    /**
    * Set the value of Id / id.
    *
    * Must not be null.
    * @param $value string
    */
    public function setId($value)
    {
        $this->validateString('Id', $value);
        $this->validateNotNull('Id', $value);

        if ($this->data['id'] === $value) {
            return;
        }

        $this->data['id'] = $value;
        $this->setModified('id');
    }

    /**
    * Set the value of DateRun / date_run.
    *
    * @param $value \DateTime
    */
    public function setDateRun($value)
    {
        $this->validateDate('DateRun', $value);

        if ($this->data['date_run'] === $value) {
            return;
        }

        $this->data['date_run'] = $value;
        $this->setModified('date_run');
    }
}
