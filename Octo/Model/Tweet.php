<?php

/**
 * Tweet model for table: tweet
 */

namespace Octo\Model;

use Octo\Model\Base\TweetBase;

/**
 * Tweet Model
 * @uses Octo\Model\Base\TweetBase
 */
class Tweet extends TweetBase
{
    /**
     * Set the value of TwitterId / twitter_id.
     *
     * @param $value string
     */
    public function setTwitterId($value)
    {
        parent::setTwitterId((string) $value);
    }
}
