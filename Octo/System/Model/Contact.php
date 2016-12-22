<?php

/**
 * Contact model for table: contact
 */

namespace Octo\System\Model;

use Octo;
use Octo\System\Searchable;

/**
 * Contact Model
 * @uses Octo\System\Model\Base\ContactBaseBase
 */
class Contact extends Base\ContactBase implements Searchable
{
    public function getSearchId() : int
    {
        return $this->getId();
    }

    public function getSearchContent() : string
    {
        return $this->getFirstName() . ' ' . $this->getLastName() . ' ' . $this->getEmail();
    }

    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

	public function setDateOfBirth($value) : self
    {
        if (is_array($value)) {
            $value = new \DateTime($value['year'] . '-' . $value['month'] . '-' . $value['day']);
        }

        return parent::setDateOfBirth($value);
    }
}
