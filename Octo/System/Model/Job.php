<?php

/**
 * Job model for table: job */

namespace Octo\System\Model;

use Octo;

/**
 * Job Model
 */
class Job extends Base\JobBase
{
    const STATUS_PENDING = 0;
    const STATUS_RUNNING = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAILURE = 3;

    const PRIORITY_LOW       = 50;
    const PRIORITY_NORMAL    = 10;
    const PRIORITY_HIGH      = 5;
    const PRIORITY_IMMEDIATE = 1;
}
