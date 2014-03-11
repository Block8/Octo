<?php
namespace Octo\Form\Element;

use b8\Form\Element;
use b8\Form\FieldSet;
use b8\Form\Element\Select;

class DateOfBirth extends FieldSet
{
    public function __construct($name = null)
    {
        parent::__construct($name);

        $year = $name . '[year]';
        $month = $name . '[month]';
        $day = $name . '[day]';

        $year = Select::create($year, null, true);
        $month = Select::create($month, null, true);
        $day = Select::create($day, null, true);

        $yearOptions = [];
        for ($i = date('Y') - 100; $i < date('Y'); $i++) {
            $yearOptions[$i] = $i;
        }

        $monthOptions = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];

        $dayOptions = [];
        for ($i = 1; $i <= 31; $i++) {
            $dayOptions[$i] = $i;
        }

        $year->setOptions($yearOptions);
        $month->setOptions($monthOptions);
        $day->setOptions($dayOptions);

        $this->addField($year);
        $this->addField($month);
        $this->addField($day);
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
