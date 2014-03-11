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

        $this->addField(Text::create($firstname, 'First name', true));
        $this->addField(Text::create($lastname, 'Last name', true));
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
