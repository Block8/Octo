<?php

/**
 * SearchIndex base model for table: search_index
 */

namespace Octo\System\Model\Base;

use b8\Store\Factory;

/**
 * SearchIndex Base Model
 */
trait SearchIndexBase
{
    protected function init()
    {
        $this->tableName = 'search_index';
        $this->modelName = 'SearchIndex';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['word'] = null;
        $this->getters['word'] = 'getWord';
        $this->setters['word'] = 'setWord';
        $this->data['model'] = null;
        $this->getters['model'] = 'getModel';
        $this->setters['model'] = 'setModel';
        $this->data['content_id'] = null;
        $this->getters['content_id'] = 'getContentId';
        $this->setters['content_id'] = 'setContentId';
        $this->data['instances'] = null;
        $this->getters['instances'] = 'getInstances';
        $this->setters['instances'] = 'setInstances';

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
    * Get the value of Word / word.
    *
    * @return string
    */
    public function getWord()
    {
        $rtn = $this->data['word'];

        return $rtn;
    }

    /**
    * Get the value of Model / model.
    *
    * @return string
    */
    public function getModel()
    {
        $rtn = $this->data['model'];

        return $rtn;
    }

    /**
    * Get the value of ContentId / content_id.
    *
    * @return string
    */
    public function getContentId()
    {
        $rtn = $this->data['content_id'];

        return $rtn;
    }

    /**
    * Get the value of Instances / instances.
    *
    * @return int
    */
    public function getInstances()
    {
        $rtn = $this->data['instances'];

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
    * Set the value of Word / word.
    *
    * Must not be null.
    * @param $value string
    */
    public function setWord($value)
    {
        $this->validateNotNull('Word', $value);
        $this->validateString('Word', $value);

        if ($this->data['word'] === $value) {
            return;
        }

        $this->data['word'] = $value;
        $this->setModified('word');
    }

    /**
    * Set the value of Model / model.
    *
    * Must not be null.
    * @param $value string
    */
    public function setModel($value)
    {
        $this->validateNotNull('Model', $value);
        $this->validateString('Model', $value);

        if ($this->data['model'] === $value) {
            return;
        }

        $this->data['model'] = $value;
        $this->setModified('model');
    }

    /**
    * Set the value of ContentId / content_id.
    *
    * Must not be null.
    * @param $value string
    */
    public function setContentId($value)
    {
        $this->validateNotNull('ContentId', $value);
        $this->validateString('ContentId', $value);

        if ($this->data['content_id'] === $value) {
            return;
        }

        $this->data['content_id'] = $value;
        $this->setModified('content_id');
    }

    /**
    * Set the value of Instances / instances.
    *
    * Must not be null.
    * @param $value int
    */
    public function setInstances($value)
    {
        $this->validateNotNull('Instances', $value);
        $this->validateInt('Instances', $value);

        if ($this->data['instances'] === $value) {
            return;
        }

        $this->data['instances'] = $value;
        $this->setModified('instances');
    }

}
