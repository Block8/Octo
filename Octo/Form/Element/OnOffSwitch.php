<?php
namespace Octo\Form\Element;

use b8\Form\Element\Checkbox;

class OnOffSwitch extends Checkbox
{
    protected $onText = 'On';
    protected $offText = 'Off';

    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->setCheckedValue('on');
    }

    public function setLabels($on, $off)
    {
        $this->onText = $on;
        $this->offText = $off;
    }

    public function onPreRender(&$view)
    {
        $view->on = $this->onText;
        $view->off = $this->offText;

        parent::onPreRender($view);
    }
}
