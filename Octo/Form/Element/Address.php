<?php
namespace Octo\Form\Element;

use b8\Form\Element;
use b8\Form\FieldSet;
use b8\Form\Element\Text;
use HMUK\Utilities\PostageCalculator;


class Address extends FieldSet
{
    public function __construct($name = null)
    {
        parent::__construct($name);

        $address1 = $name . '[address1]';
        $address2 = $name . '[address2]';
        $town = $name . '[town]';
        $postcode = 'postcode';

        if ($name != 'address') {
            $postcode = $name . '[' . $postcode . ']';
        }

        $countryCode = $name . '[country_code]';

        $address2 = Text::create($address2, 'Address 2', false);

        $address1 = new Element\Text($address1);
        $address1->setRequired(true);
        $address1->setLabel('Address 1');
        $address1->setPattern('.{2,100}');

        $town = new Element\Text($town);
        $town->setRequired(true);
        $town->setLabel('Town');
        $town->setPattern('^[A-Za-z0-9- ]{2,40}$');

        $postcode = new Element\Text($postcode);
        $postcode->setRequired(true);
        $postcode->setLabel('Postcode');
        $postcode->setPattern('^[A-Za-z0-9- ]{5,10}$');

        $countryCode = Element\Select::create($countryCode, 'Country', true);
        $countryCode->setOptions(PostageCalculator::$countries);
        //check why setvalue set, but does not select
        $countryCode->setValue(PostageCalculator::UK_COUNTRY_CODE);

        $this->addField($address1);
        $this->addField($address2);
        $this->addField($town);
        $this->addField($postcode);
        $this->addField($countryCode);
    }

    public function setRequired($required)
    {
        if ($required) {
            foreach ($this->children as &$child) {
                $child->setRequired(true);
            }

            $this->children[$this->getName() . '[address2]']->setRequired(false);
        } else {
            foreach ($this->children as &$child) {
                $child->setRequired(false);
            }
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
