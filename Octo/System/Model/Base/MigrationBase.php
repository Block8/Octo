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
        $this->data['version'] = null;
        $this->getters['version'] = 'getVersion';
        $this->setters['version'] = 'setVersion';
        $this->data['start_time'] = null;
        $this->getters['start_time'] = 'getStartTime';
        $this->setters['start_time'] = 'setStartTime';
        $this->data['end_time'] = null;
        $this->getters['end_time'] = 'getEndTime';
        $this->setters['end_time'] = 'setEndTime';

        // Foreign keys:
    }
    /**
    * Get the value of Version / version.
    *
    * @return int
    */
    public function getVersion()
    {
        $rtn = $this->data['version'];

        return $rtn;
    }

    /**
    * Get the value of StartTime / start_time.
    *
    * @return string
    */
    public function getStartTime()
    {
        $rtn = $this->data['start_time'];

        return $rtn;
    }

    /**
    * Get the value of EndTime / end_time.
    *
    * @return string
    */
    public function getEndTime()
    {
        $rtn = $this->data['end_time'];

        return $rtn;
    }


    /**
    * Set the value of Version / version.
    *
    * Must not be null.
    * @param $value int
    */
    public function setVersion($value)
    {
        $this->validateInt('Version', $value);
        $this->validateNotNull('Version', $value);

        if ($this->data['version'] === $value) {
            return;
        }

        $this->data['version'] = $value;
        $this->setModified('version');
    }

    /**
    * Set the value of StartTime / start_time.
    *
    * Must not be null.
    * @param $value string
    */
    public function setStartTime($value)
    {
        $this->validateString('StartTime', $value);
        $this->validateNotNull('StartTime', $value);

        if ($this->data['start_time'] === $value) {
            return;
        }

        $this->data['start_time'] = $value;
        $this->setModified('start_time');
    }

    /**
    * Set the value of EndTime / end_time.
    *
    * Must not be null.
    * @param $value string
    */
    public function setEndTime($value)
    {
        $this->validateString('EndTime', $value);
        $this->validateNotNull('EndTime', $value);

        if ($this->data['end_time'] === $value) {
            return;
        }

        $this->data['end_time'] = $value;
        $this->setModified('end_time');
    }
}
