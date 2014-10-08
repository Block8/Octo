<?php
namespace Octo\Form\Element;

use b8\Form\Element;
use b8\Form\FieldSet;
use HMUK\Utilities\PostageCalculator;

class Address extends FieldSet
{
    public function __construct($name = null)
    {
        parent::__construct($name);

        $address1 = $name . '[address1]';
        $address2 = $name . '[address2]';
        $town = $name . '[town]';
        $county = $name . '[county]';
        $postcode = 'postcode';

        if ($name != 'address') {
            $postcode = $name . '[' . $postcode . ']';
        }

        $countryCode = $name . '[country_code]';

        $address1 = new Input5($address1);
        $address1->setRequired(true);
        $address1->setLabel('Address 1');
        $address1->setPattern("(^[a-zA-Z0-9 \.\_\-\@\$\'\:\,]{2,100}$)");
        $address1->setAttributes(array('maxlength'=>100));
        $address1->setTitle('Please enter only: letters and numbers (hyphens, spaces, comma, dot may be included). Minimum 2 characters.');

        $address2 = new Input5($address2);
        $address2->setRequired(false);
        $address2->setLabel('Address 2');
        $address2->setAttributes(array('maxlength'=>100));
        $address2->setTitle('Please enter only: letters and numbers (hyphens, spaces, comma, dot may be included). Minimum 2 characters.');

        $town = new Input5($town);
        $town->setRequired(true);
        $town->setLabel('Town');
        $town->setPattern('^[A-Za-z0-9\- \.\,\']{2,40}$');
        $town->setAttributes(array('maxlength'=>40));
        $town->setTitle('Please enter only: letters and numbers (hyphens, spaces, comma, dot may be included). Maximum 40 characters.');

        $county = new Input5($county);
        $county->setLabel('County');
        $county->setRequired(false);
        $county->setAttributes(array('maxlength'=>40));
        $county->setTitle('Please enter only: letters and numbers (hyphens, spaces, comma, dot may be included). Maximum 40 characters.');
        //$county->setPattern("(^[a-zA-Z0-9 \.\_\-\@\$\'\:\,]{2,40})$");

        $postcode = new Input5($postcode);
        $postcode->setRequired(true);
        $postcode->setLabel('Postcode');
        $postcode->setPattern('^[A-Za-z0-9- ]{5,10}$');
        $postcode->setAttributes(array('maxlength'=>10));
        $postcode->setTitle('Please enter only: letters and numbers (hyphens and spaces may be included). Minimum 5, maximum 10 characters.');

        $countryCode = Element\Select::create($countryCode, 'Country', true);
        $countryCode->setOptions(PostageCalculator::$countries);
        //check why setvalue set, but does not select
        $countryCode->setValue(PostageCalculator::UK_COUNTRY_CODE);

        $this->addField($address1);
        $this->addField($address2);
        $this->addField($town);
        $this->addField($county);
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
            $this->children[$this->getName() . '[county]']->setRequired(false);
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
