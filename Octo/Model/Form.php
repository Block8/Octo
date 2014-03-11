<?php

/**
 * Form model for table: form
 */

namespace Octo\Model;

use Octo\Model\Base\FormBase;

/**
 * Form Model
 * @uses Octo\Model\Base\FormBase
 */
class Form extends FormBase
{
    public function setDefinition($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        parent::setDefinition($value);
    }

    public function getDefinition()
    {
        $value = parent::getDefinition();

        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        return $value;
    }
}
