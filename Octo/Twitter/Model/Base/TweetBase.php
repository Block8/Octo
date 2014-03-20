<?php

/**
 * Tweet base model for table: tweet
 */

namespace Octo\Twitter\Model\Base;

use b8\Store\Factory;

/**
 * Tweet Base Model
 */
trait TweetBase
{
    protected function init()
    {
        $this->tableName = 'tweet';
        $this->modelName = 'Tweet';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['twitter_id'] = null;
        $this->getters['twitter_id'] = 'getTwitterId';
        $this->setters['twitter_id'] = 'setTwitterId';
        $this->data['text'] = null;
        $this->getters['text'] = 'getText';
        $this->setters['text'] = 'setText';
        $this->data['html_text'] = null;
        $this->getters['html_text'] = 'getHtmlText';
        $this->setters['html_text'] = 'setHtmlText';
        $this->data['screenname'] = null;
        $this->getters['screenname'] = 'getScreenname';
        $this->setters['screenname'] = 'setScreenname';
        $this->data['posted'] = null;
        $this->getters['posted'] = 'getPosted';
        $this->setters['posted'] = 'setPosted';
        $this->data['created_date'] = null;
        $this->getters['created_date'] = 'getCreatedDate';
        $this->setters['created_date'] = 'setCreatedDate';
        $this->data['scope'] = null;
        $this->getters['scope'] = 'getScope';
        $this->setters['scope'] = 'setScope';

        // Foreign keys:
    }
    /**
    * Get the value of Id / id.
    *
    * @return int
    */
    public function getId()
    {
        $rtn = $this->data['id'];

        return $rtn;
    }

    /**
    * Get the value of TwitterId / twitter_id.
    *
    * @return string
    */
    public function getTwitterId()
    {
        $rtn = $this->data['twitter_id'];

        return $rtn;
    }

    /**
    * Get the value of Text / text.
    *
    * @return string
    */
    public function getText()
    {
        $rtn = $this->data['text'];

        return $rtn;
    }

    /**
    * Get the value of HtmlText / html_text.
    *
    * @return string
    */
    public function getHtmlText()
    {
        $rtn = $this->data['html_text'];

        return $rtn;
    }

    /**
    * Get the value of Screenname / screenname.
    *
    * @return string
    */
    public function getScreenname()
    {
        $rtn = $this->data['screenname'];

        return $rtn;
    }

    /**
    * Get the value of Posted / posted.
    *
    * @return \DateTime
    */
    public function getPosted()
    {
        $rtn = $this->data['posted'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

        return $rtn;
    }

    /**
    * Get the value of CreatedDate / created_date.
    *
    * @return \DateTime
    */
    public function getCreatedDate()
    {
        $rtn = $this->data['created_date'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

        return $rtn;
    }

    /**
    * Get the value of Scope / scope.
    *
    * @return string
    */
    public function getScope()
    {
        $rtn = $this->data['scope'];

        return $rtn;
    }

    /**
    * Set the value of Id / id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setId($value)
    {
        $this->validateNotNull('Id', $value);
        $this->validateInt('Id', $value);

        if ($this->data['id'] === $value) {
            return;
        }

        $this->data['id'] = $value;
        $this->setModified('id');
    }

    /**
    * Set the value of TwitterId / twitter_id.
    *
    * @param $value string
    */
    public function setTwitterId($value)
    {
        $this->validateString('TwitterId', $value);

        if ($this->data['twitter_id'] === $value) {
            return;
        }

        $this->data['twitter_id'] = $value;
        $this->setModified('twitter_id');
    }

    /**
    * Set the value of Text / text.
    *
    * @param $value string
    */
    public function setText($value)
    {
        $this->validateString('Text', $value);

        if ($this->data['text'] === $value) {
            return;
        }

        $this->data['text'] = $value;
        $this->setModified('text');
    }

    /**
    * Set the value of HtmlText / html_text.
    *
    * @param $value string
    */
    public function setHtmlText($value)
    {
        $this->validateString('HtmlText', $value);

        if ($this->data['html_text'] === $value) {
            return;
        }

        $this->data['html_text'] = $value;
        $this->setModified('html_text');
    }

    /**
    * Set the value of Screenname / screenname.
    *
    * @param $value string
    */
    public function setScreenname($value)
    {
        $this->validateString('Screenname', $value);

        if ($this->data['screenname'] === $value) {
            return;
        }

        $this->data['screenname'] = $value;
        $this->setModified('screenname');
    }

    /**
    * Set the value of Posted / posted.
    *
    * @param $value \DateTime
    */
    public function setPosted($value)
    {
        $this->validateDate('Posted', $value);

        if ($this->data['posted'] === $value) {
            return;
        }

        $this->data['posted'] = $value;
        $this->setModified('posted');
    }

    /**
    * Set the value of CreatedDate / created_date.
    *
    * @param $value \DateTime
    */
    public function setCreatedDate($value)
    {
        $this->validateDate('CreatedDate', $value);

        if ($this->data['created_date'] === $value) {
            return;
        }

        $this->data['created_date'] = $value;
        $this->setModified('created_date');
    }

    /**
    * Set the value of Scope / scope.
    *
    * @param $value string
    */
    public function setScope($value)
    {
        $this->validateString('Scope', $value);

        if ($this->data['scope'] === $value) {
            return;
        }

        $this->data['scope'] = $value;
        $this->setModified('scope');
    }

}
