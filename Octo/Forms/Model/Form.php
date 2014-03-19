<?php

/**
 * Form model for table: form
 */

namespace Octo\Model;

use Octo;

/**
 * Form Model
 * @uses Octo\Model\Base\FormBase
 */
class Form extends Octo\Model
{
    use Base\FormBase;

    public function setDefinition($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        $this->validateNotNull('Definition', $value);
        $this->validateString('Definition', $value);

        if ($this->data['definition'] === $value) {
            return;
        }

        $this->data['definition'] = $value;
        $this->setModified('definition');
    }


    public function getDefinition()
    {
        $value = $this->data['definition'];

        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        return $value;
    }
}
