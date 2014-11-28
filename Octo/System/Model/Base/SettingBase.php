<?php

/**
 * Setting base model for table: setting
 */

namespace Octo\System\Model\Base;

use b8\Store\Factory;

/**
 * Setting Base Model
 */
trait SettingBase
{
    protected function init()
    {
        $this->tableName = 'setting';
        $this->modelName = 'Setting';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['key'] = null;
        $this->getters['key'] = 'getKey';
        $this->setters['key'] = 'setKey';
        $this->data['value'] = null;
        $this->getters['value'] = 'getValue';
        $this->setters['value'] = 'setValue';
        $this->data['scope'] = null;
        $this->getters['scope'] = 'getScope';
        $this->setters['scope'] = 'setScope';
        $this->data['hidden'] = null;
        $this->getters['hidden'] = 'getHidden';
        $this->setters['hidden'] = 'setHidden';

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
    * Get the value of Key / key.
    *
    * @return string
    */
    public function getKey()
    {
        $rtn = $this->data['key'];

        return $rtn;
    }

    /**
    * Get the value of Value / value.
    *
    * @return string
    */
    public function getValue()
    {
        $rtn = $this->data['value'];

        return $rtn;
    }

    /**
    * Get the value of Scope / scope.
    *
    * @return string
    */
    public function getScope()
    {
        $rtn = $this->data['scope'];

        return $rtn;
    }

    /**
    * Get the value of Hidden / hidden.
    *
    * @return int
    */
    public function getHidden()
    {
        $rtn = $this->data['hidden'];

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
        $this->validateInt('Id', $value);
        $this->validateNotNull('Id', $value);

        if ($this->data['id'] === $value) {
            return;
        }

        $this->data['id'] = $value;
        $this->setModified('id');
    }

    /**
    * Set the value of Key / key.
    *
    * Must not be null.
    * @param $value string
    */
    public function setKey($value)
    {
        $this->validateString('Key', $value);
        $this->validateNotNull('Key', $value);

        if ($this->data['key'] === $value) {
            return;
        }

        $this->data['key'] = $value;
        $this->setModified('key');
    }

    /**
    * Set the value of Value / value.
    *
    * @param $value string
    */
    public function setValue($value)
    {
        $this->validateString('Value', $value);

        if ($this->data['value'] === $value) {
            return;
        }

        $this->data['value'] = $value;
        $this->setModified('value');
    }

    /**
    * Set the value of Scope / scope.
    *
    * Must not be null.
    * @param $value string
    */
    public function setScope($value)
    {
        $this->validateString('Scope', $value);
        $this->validateNotNull('Scope', $value);

        if ($this->data['scope'] === $value) {
            return;
        }

        $this->data['scope'] = $value;
        $this->setModified('scope');
    }

    /**
    * Set the value of Hidden / hidden.
    *
    * @param $value int
    */
    public function setHidden($value)
    {
        $this->validateInt('Hidden', $value);

        if ($this->data['hidden'] === $value) {
            return;
        }

        $this->data['hidden'] = $value;
        $this->setModified('hidden');
    }
}
