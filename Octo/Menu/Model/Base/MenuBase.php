<?php

/**
 * Menu base model for table: menu
 */

namespace Octo\Menu\Model\Base;

use b8\Store\Factory;

/**
 * Menu Base Model
 */
trait MenuBase
{
    protected function init()
    {
        $this->tableName = 'menu';
        $this->modelName = 'Menu';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['name'] = null;
        $this->getters['name'] = 'getName';
        $this->setters['name'] = 'setName';
        $this->data['template_tag'] = null;
        $this->getters['template_tag'] = 'getTemplateTag';
        $this->setters['template_tag'] = 'setTemplateTag';

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
    * Get the value of Name / name.
    *
    * @return string
    */
    public function getName()
    {
        $rtn = $this->data['name'];

        return $rtn;
    }

    /**
    * Get the value of TemplateTag / template_tag.
    *
    * @return string
    */
    public function getTemplateTag()
    {
        $rtn = $this->data['template_tag'];

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
    * Set the value of Name / name.
    *
    * @param $value string
    */
    public function setName($value)
    {
        $this->validateString('Name', $value);

        if ($this->data['name'] === $value) {
            return;
        }

        $this->data['name'] = $value;
        $this->setModified('name');
    }

    /**
    * Set the value of TemplateTag / template_tag.
    *
    * @param $value string
    */
    public function setTemplateTag($value)
    {
        $this->validateString('TemplateTag', $value);

        if ($this->data['template_tag'] === $value) {
            return;
        }

        $this->data['template_tag'] = $value;
        $this->setModified('template_tag');
    }
}
