<?php

/**
 * ContentItem base model for table: content_item
 */

namespace Octo\System\Model\Base;

use b8\Store\Factory;

/**
 * ContentItem Base Model
 */
trait ContentItemBase
{
    protected function init()
    {
        $this->tableName = 'content_item';
        $this->modelName = 'ContentItem';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['content'] = null;
        $this->getters['content'] = 'getContent';
        $this->setters['content'] = 'setContent';

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
    * Get the value of Content / content.
    *
    * @return string
    */
    public function getContent()
    {
        $rtn = $this->data['content'];

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
        $this->validateNotNull('Id', $value);
        $this->validateString('Id', $value);

        if ($this->data['id'] === $value) {
            return;
        }

        $this->data['id'] = $value;
        $this->setModified('id');
    }

    /**
    * Set the value of Content / content.
    *
    * Must not be null.
    * @param $value string
    */
    public function setContent($value)
    {
        $this->validateNotNull('Content', $value);
        $this->validateString('Content', $value);

        if ($this->data['content'] === $value) {
            return;
        }

        $this->data['content'] = $value;
        $this->setModified('content');
    }

}
