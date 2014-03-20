<?php

/**
 * GaPageView base model for table: ga_page_view
 */

namespace Octo\Analytics\Model\Base;

use b8\Store\Factory;

/**
 * GaPageView Base Model
 */
trait GaPageViewBase
{
    protected function init()
    {
        $this->tableName = 'ga_page_view';
        $this->modelName = 'GaPageView';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['date'] = null;
        $this->getters['date'] = 'getDate';
        $this->setters['date'] = 'setDate';
        $this->data['updated'] = null;
        $this->getters['updated'] = 'getUpdated';
        $this->setters['updated'] = 'setUpdated';
        $this->data['value'] = null;
        $this->getters['value'] = 'getValue';
        $this->setters['value'] = 'setValue';
        $this->data['metric'] = null;
        $this->getters['metric'] = 'getMetric';
        $this->setters['metric'] = 'setMetric';

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
    * Get the value of Date / date.
    *
    * @return \DateTime
    */
    public function getDate()
    {
        $rtn = $this->data['date'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

        return $rtn;
    }

    /**
    * Get the value of Updated / updated.
    *
    * @return \DateTime
    */
    public function getUpdated()
    {
        $rtn = $this->data['updated'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

        return $rtn;
    }

    /**
    * Get the value of Value / value.
    *
    * @return int
    */
    public function getValue()
    {
        $rtn = $this->data['value'];

        return $rtn;
    }

    /**
    * Get the value of Metric / metric.
    *
    * @return string
    */
    public function getMetric()
    {
        $rtn = $this->data['metric'];

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
    * Set the value of Date / date.
    *
    * @param $value \DateTime
    */
    public function setDate($value)
    {
        $this->validateDate('Date', $value);

        if ($this->data['date'] === $value) {
            return;
        }

        $this->data['date'] = $value;
        $this->setModified('date');
    }

    /**
    * Set the value of Updated / updated.
    *
    * @param $value \DateTime
    */
    public function setUpdated($value)
    {
        $this->validateDate('Updated', $value);

        if ($this->data['updated'] === $value) {
            return;
        }

        $this->data['updated'] = $value;
        $this->setModified('updated');
    }

    /**
    * Set the value of Value / value.
    *
    * @param $value int
    */
    public function setValue($value)
    {
        $this->validateInt('Value', $value);

        if ($this->data['value'] === $value) {
            return;
        }

        $this->data['value'] = $value;
        $this->setModified('value');
    }

    /**
    * Set the value of Metric / metric.
    *
    * @param $value string
    */
    public function setMetric($value)
    {
        $this->validateString('Metric', $value);

        if ($this->data['metric'] === $value) {
            return;
        }

        $this->data['metric'] = $value;
        $this->setModified('metric');
    }

}
