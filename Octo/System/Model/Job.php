<?php

/**
 * Job model for table: job */

namespace Octo\System\Model;

use Octo;

/**
 * Job Model
 */
class Job extends Octo\Model
{
    const STATUS_PENDING = 0;
    const STATUS_RUNNING = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAILURE = 3;

    const PRIORITY_LOW       = 50;
    const PRIORITY_NORMAL    = 10;
    const PRIORITY_HIGH      = 5;
    const PRIORITY_IMMEDIATE = 1;

    use Base\JobBase;

    public function setData(array $value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        if ($this->data['data'] === $value) {
            return;
        }

        $this->data['data'] = $value;
        $this->setModified('data');
    }

    public function getData()
    {
        if (!empty($this->data['data'])) {
            return json_decode($this->data['data'], true);
        }

        return null;
    }

    public function toJson()
    {
        return json_encode($this->getDataArray());
    }
}
