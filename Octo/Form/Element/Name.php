<?php
namespace Octo\Form\Element;

use b8\Form\Element;
use b8\Form\FieldSet;
use b8\Form\Element\Text;

class Name extends FieldSet
{
    public function __construct($name = null)
    {
        parent::__construct($name);

        $firstname = 'first_name';
        $lastname = 'last_name';

        if ($name != 'name') {
            $firstname = $name . '[first_name]';
            $lastname = $name . '[last_name]';
        }

        $firstname = new Element\Text($firstname);
        $firstname->setRequired(true);
        $firstname->setLabel('First name');
        $firstname->setPattern('.{1,40}');

        $lastname = new Element\Text($lastname);
        $lastname->setRequired(true);
        $lastname->setLabel('Last name');
        $lastname->setPattern('.{1,40}');

        $this->addField($firstname);
        $this->addField($lastname);
    }

    public function setRequired($required)
    {
        foreach ($this->getChildren() as &$child) {
            $child->setRequired($required);
        }
    }

    public function setParent(Element $parent)
    {
        parent::setParent($parent);

        foreach ($this->getChildren() as &$child) {
            $child->setViewLoader($this->getViewLoader());
        }
    }
}
