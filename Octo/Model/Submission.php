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

        parent::setExtra($value);
    }

    public function getExtra()
    {
        $value = parent::getExtra();

        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        return $value;
    }
}
