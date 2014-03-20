<?php

/**
 * Contact model for table: contact
 */

namespace Octo\System\Model;

use Octo;

/**
 * Contact Model
 * @uses Octo\System\Model\Base\ContactBaseBase
 */
class Contact extends Octo\Model
{
    use Base\ContactBase;

    public function setDateOfBirth($value)
    {
        if (is_array($value)) {
            $value = new \DateTime($value['year'] . '-' . $value['month'] . '-' . $value['day']);
        }

        $this->validateDate('DateOfBirth', $value);

        if ($this->data['date_of_birth'] === $value) {
            return;
        }

        $this->data['date_of_birth'] = $value;
        $this->setModified('date_of_birth');
    }

    public function setAddress($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        $this->validateString('Address', $value);

        if ($this->data['address'] === $value) {
            return;
        }

        $this->data['address'] = $value;
        $this->setModified('address');
    }

    public function getAddress()
    {
        $value = $this->data['address'];

        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        return $value;
    }
}
