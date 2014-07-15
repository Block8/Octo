<?php
namespace Octo\Form\Element;

use b8\Form\Element\Text;

class TextBlock extends Text
{
    public function getRequired()
    {
        return false;
    }

    public function validate()
    {
        return true;
    }
}
