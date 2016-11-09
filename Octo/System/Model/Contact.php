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
class Contact extends Base\ContactBase
{
    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

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

        if (is_null($this->data['postcode'])) {
            $matches = [];

            if (preg_match('/([a-zA-Z]{1,2}[0-9]{1,2}\s?[0-9][a-zA-Z]{1,2})/', $value, $matches)) {
                $this->setPostcode($matches[1]);
                $value = str_replace($matches[1], '', $value);
            }
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
        $address = ['address1' => null, 'address2' => null, 'town' => null];

        if (!empty($this->data['address'])) {
            $addressString = $this->data['address'];
            $addressArray = json_decode($addressString, true);

            if (is_null($addressArray)) {
                $addressArray = explode(PHP_EOL, $addressString);

                $address['address1'] = array_shift($addressArray);
                $address['town'] = array_pop($addressArray);
                $address['address2'] = implode(', ', $addressArray);
            } else {
                $address = $addressArray;
            }
        }

        return $address;
    }
}
