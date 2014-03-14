<?php

/**
 * Contact model for table: contact
 */

namespace Octo\Model;

use Octo;

/**
 * Contact Model
 * @uses Octo\Model\Base\ContactBase
 */
class Contact extends Octo\Model
{
    use Base\ContactBase;

    public function setDateOfBirth($value)
    {
        if (is_array($value)) {
            $value = new \DateTime($value['year'] . '-' . $value['month'] . '-' . $value['day']);
        }

        parent::setDateOfBirth($value);
    }

    public function setAddress($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        parent::setAddress($value);
    }

    public function getAddress()
    {
        $value = parent::getAddress();

        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        return $value;
    }
}
