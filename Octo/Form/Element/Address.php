<?php
namespace Octo\Form\Element;

use b8\Config;
use b8\Form\Element;
use b8\Form\FieldSet;
use b8\Form\Element\Text;

class Address extends FieldSet
{
    protected $countryEnabled = false;

    public function __construct($name = null, $includeCountry = false)
    {
        $this->countryEnabled = $includeCountry;

        parent::__construct($name);

        $address1 = $name . '[address1]';
        $address2 = $name . '[address2]';
        $town = $name . '[town]';
        $postcode = 'postcode';

        if ($name != 'address') {
            $postcode = $name . '[' . $postcode . ']';
        }

        $address1 = Text::create($address1, 'Address 1', true);
        $address1->setId($name . '_address1');

        $address2 = Text::create($address2, 'Address 2', false);
        $address2->setId($name . '_address2');

        $town = Text::create($town, 'Town', true);
        $town->setId($name . '_town');

        $postcode = Text::create($postcode, 'Postcode', true);
        $postcode->setId($name . '_postcode');

        $postcode->setClass('postcode');

        $this->addField($address1);
        $this->addField($address2);
        $this->addField($town);
        $this->addField($postcode);

        if ($includeCountry) {
            $country = Country::create($name . '[country]', 'Country', true, 'country');
            $country->setId($name . '_country');
            $this->addField($country);
        }
    }

    public function setRequired($required)
    {
        if ($required) {
            foreach ($this->children as &$child) {
                $child->setRequired(true);
            }

            $this->children[$this->getName() . '_address2']->setRequired(false);
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
