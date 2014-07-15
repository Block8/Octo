<?php

/**
 * Tweet model for table: tweet
 */

namespace Octo\Twitter\Model;

use Octo;

/**
 * Tweet Model
 * @uses Octo\Twitter\Model\Base\TweetBaseBase
 */
class Tweet extends Octo\Model
{
    use Base\TweetBase;

    /**
     * Set the value of TwitterId / twitter_id.
     *
     * @param $value string
     */
    public function setTwitterId($value)
    {
        $value = (string)$value;
        $this->validateString('TwitterId', $value);

        if ($this->data['twitter_id'] === $value) {
            return;
        }

        $this->data['twitter_id'] = $value;
        $this->setModified('twitter_id');
    }
}
