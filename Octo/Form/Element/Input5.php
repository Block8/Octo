<?php

namespace Octo\Form\Element;

use b8\Form\Input;
use b8\View;

class Input5 extends Input
{
    protected $type = 'text';
    protected $title;
    protected $addon;
    protected $attributes = array(); //input attributes like maxlength


    protected function onPreRender(View &$view)
    {
        parent::onPreRender($view);
        $view->type = $this->type;
        $view->attributes = $this->attributes;
        $view->title = $this->title;
        $view->addon = $this->addon;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($value)
    {
        $this->type = $value;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($value)
    {
        $this->title = $value;
        return $this;
    }

    public function getAddon()
    {
        return $this->addon;
    }

    public function setAddon($value)
    {
        $this->addon = $value;
        return $this;
    }

    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

}
