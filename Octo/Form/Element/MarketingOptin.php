<?php
namespace Octo\Form\Element;

use b8\Form\Element;
use b8\Form\Element\Checkbox;

class MarketingOptin extends Checkbox
{
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->setCheckedValue(1);
        $this->setValue(1);
    }
}
