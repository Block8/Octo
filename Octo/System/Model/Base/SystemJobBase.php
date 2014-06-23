<?php

/**
 * SystemJob base model for table: system_job
 */

namespace Octo\System\Model\Base;

use b8\Store\Factory;

/**
 * SystemJob Base Model
 */
trait SystemJobBase
{
    protected function init()
    {
        $this->tableName = 'system_job';
        $this->modelName = 'SystemJob';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['command'] = null;
        $this->getters['command'] = 'getCommand';
        $this->setters['command'] = 'setCommand';
        $this->data['frequency'] = null;
        $this->getters['frequency'] = 'getFrequency';
        $this->setters['frequency'] = 'setFrequency';
        $this->data['run_date'] = null;
        $this->getters['run_date'] = 'getRunDate';
        $this->setters['run_date'] = 'setRunDate';

        // Foreign keys:
    }
    /**
    * Get the value of Id / id.
    *
    * @return int
    */
    public function getId()
    {
        $rtn = $this->data['id'];

        return $rtn;
    }

    /**
    * Get the value of Command / command.
    *
    * @return string
    */
    public function getCommand()
    {
        $rtn = $this->data['command'];

        return $rtn;
    }

    /**
    * Get the value of Frequency / frequency.
    *
    * @return string
    */
    public function getFrequency()
    {
        $rtn = $this->data['frequency'];

        return $rtn;
    }

    /**
    * Get the value of RunDate / run_date.
    *
    * @return \DateTime
    */
    public function getRunDate()
    {
        $rtn = $this->data['run_date'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

        return $rtn;
    }


    /**
    * Set the value of Id / id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setId($value)
    {
        $this->validateNotNull('Id', $value);
        $this->validateInt('Id', $value);

        if ($this->data['id'] === $value) {
            return;
        }

        $this->data['id'] = $value;
        $this->setModified('id');
    }

    /**
    * Set the value of Command / command.
    *
    * Must not be null.
    * @param $value string
    */
    public function setCommand($value)
    {
        $this->validateNotNull('Command', $value);
        $this->validateString('Command', $value);

        if ($this->data['command'] === $value) {
            return;
        }

        $this->data['command'] = $value;
        $this->setModified('command');
    }

    /**
    * Set the value of Frequency / frequency.
    *
    * Must not be null.
    * @param $value string
    */
    public function setFrequency($value)
    {
        $this->validateNotNull('Frequency', $value);
        $this->validateString('Frequency', $value);

        if ($this->data['frequency'] === $value) {
            return;
        }

        $this->data['frequency'] = $value;
        $this->setModified('frequency');
    }

    /**
    * Set the value of RunDate / run_date.
    *
    * Must not be null.
    * @param $value \DateTime
    */
    public function setRunDate($value)
    {
        $this->validateNotNull('RunDate', $value);
        $this->validateDate('RunDate', $value);

        if ($this->data['run_date'] === $value) {
            return;
        }

        $this->data['run_date'] = $value;
        $this->setModified('run_date');
    }
}
