<?php

/**
 * Form base model for table: form
 */

namespace Octo\Model\Base;

use b8\Store\Factory;

/**
 * Form Base Model
 */
trait FormBase
{
    protected function init()
    {
        $this->tableName = 'form';
        $this->modelName = 'Form';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['title'] = null;
        $this->getters['title'] = 'getTitle';
        $this->setters['title'] = 'setTitle';
        $this->data['recipients'] = null;
        $this->getters['recipients'] = 'getRecipients';
        $this->setters['recipients'] = 'setRecipients';
        $this->data['definition'] = null;
        $this->getters['definition'] = 'getDefinition';
        $this->setters['definition'] = 'setDefinition';
        $this->data['thankyou_message'] = null;
        $this->getters['thankyou_message'] = 'getThankyouMessage';
        $this->setters['thankyou_message'] = 'setThankyouMessage';

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
    * Get the value of Title / title.
    *
    * @return string
    */
    public function getTitle()
    {
        $rtn = $this->data['title'];

        return $rtn;
    }

    /**
    * Get the value of Recipients / recipients.
    *
    * @return string
    */
    public function getRecipients()
    {
        $rtn = $this->data['recipients'];

        return $rtn;
    }

    /**
    * Get the value of Definition / definition.
    *
    * @return string
    */
    public function getDefinition()
    {
        $rtn = $this->data['definition'];

        return $rtn;
    }

    /**
    * Get the value of ThankyouMessage / thankyou_message.
    *
    * @return string
    */
    public function getThankyouMessage()
    {
        $rtn = $this->data['thankyou_message'];

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
    * Set the value of Title / title.
    *
    * Must not be null.
    * @param $value string
    */
    public function setTitle($value)
    {
        $this->validateNotNull('Title', $value);
        $this->validateString('Title', $value);

        if ($this->data['title'] === $value) {
            return;
        }

        $this->data['title'] = $value;
        $this->setModified('title');
    }

    /**
    * Set the value of Recipients / recipients.
    *
    * @param $value string
    */
    public function setRecipients($value)
    {
        $this->validateString('Recipients', $value);

        if ($this->data['recipients'] === $value) {
            return;
        }

        $this->data['recipients'] = $value;
        $this->setModified('recipients');
    }

    /**
    * Set the value of Definition / definition.
    *
    * Must not be null.
    * @param $value string
    */
    public function setDefinition($value)
    {
        $this->validateNotNull('Definition', $value);
        $this->validateString('Definition', $value);

        if ($this->data['definition'] === $value) {
            return;
        }

        $this->data['definition'] = $value;
        $this->setModified('definition');
    }

    /**
    * Set the value of ThankyouMessage / thankyou_message.
    *
    * @param $value string
    */
    public function setThankyouMessage($value)
    {
        $this->validateString('ThankyouMessage', $value);

        if ($this->data['thankyou_message'] === $value) {
            return;
        }

        $this->data['thankyou_message'] = $value;
        $this->setModified('thankyou_message');
    }

}
