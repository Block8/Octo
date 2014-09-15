<?php
namespace Octo\Form\Element;

use b8\Form\Element\Select;

class Title extends Select
{
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->setPattern('(mr|mrs|miss|ms|dr|prof|reverend|sir|lady)');
        $this->setOptions([
            'mr' => 'Mr',
            'mrs' => 'Mrs',
            'miss' => 'Miss',
            'ms' => 'Ms',
            'dr' => 'Doctor',
            'prof' => 'Professor',
            'reverend' => 'Reverend',
            'sir' => 'Sir',
            'lady' => 'Lady'
        ]);
    }
}
