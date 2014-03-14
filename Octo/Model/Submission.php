<?php

/**
 * Submission model for table: submission
 */

namespace Octo\Model;

use Octo;

/**
 * Submission Model
 * @uses Octo\Model\Base\SubmissionBase
 */
class Submission extends Octo\Model
{
    use Base\SubmissionBase;

    public function setExtra($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        $this->validateString('Extra', $value);

        if ($this->data['extra'] === $value) {
            return;
        }

        $this->data['extra'] = $value;
        $this->setModified('extra');
    }

    public function getExtra()
    {
        $value = $this->data['extra'];

        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        return $value;
    }
}
