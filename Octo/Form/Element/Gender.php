<?php
namespace Octo\Form\Element;

use b8\Form\Element\Select;

class Gender extends Select
{
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->setPattern('(male|female|other)');
        $this->setOptions(['male' => 'Male', 'female' => 'Female', 'other' => 'Other']);
    }
}
