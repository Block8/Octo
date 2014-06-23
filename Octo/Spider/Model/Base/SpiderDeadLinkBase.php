<?php

/**
 * SpiderDeadLink base model for table: spider_dead_link
 */

namespace Octo\Spider\Model\Base;

use b8\Store\Factory;

/**
 * SpiderDeadLink Base Model
 */
trait SpiderDeadLinkBase
{
    protected function init()
    {
        $this->tableName = 'spider_dead_link';
        $this->modelName = 'SpiderDeadLink';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['url'] = null;
        $this->getters['url'] = 'getUrl';
        $this->setters['url'] = 'setUrl';
        $this->data['parent_url'] = null;
        $this->getters['parent_url'] = 'getParentUrl';
        $this->setters['parent_url'] = 'setParentUrl';
        $this->data['first_scan_epoch'] = null;
        $this->getters['first_scan_epoch'] = 'getFirstScanEpoch';
        $this->setters['first_scan_epoch'] = 'setFirstScanEpoch';
        $this->data['last_scan_epoch'] = null;
        $this->getters['last_scan_epoch'] = 'getLastScanEpoch';
        $this->setters['last_scan_epoch'] = 'setLastScanEpoch';
        $this->data['http_response_code'] = null;
        $this->getters['http_response_code'] = 'getHttpResponseCode';
        $this->setters['http_response_code'] = 'setHttpResponseCode';

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
    * Get the value of Url / url.
    *
    * @return string
    */
    public function getUrl()
    {
        $rtn = $this->data['url'];

        return $rtn;
    }

    /**
    * Get the value of ParentUrl / parent_url.
    *
    * @return string
    */
    public function getParentUrl()
    {
        $rtn = $this->data['parent_url'];

        return $rtn;
    }

    /**
    * Get the value of FirstScanEpoch / first_scan_epoch.
    *
    * @return int
    */
    public function getFirstScanEpoch()
    {
        $rtn = $this->data['first_scan_epoch'];

        return $rtn;
    }

    /**
    * Get the value of LastScanEpoch / last_scan_epoch.
    *
    * @return int
    */
    public function getLastScanEpoch()
    {
        $rtn = $this->data['last_scan_epoch'];

        return $rtn;
    }

    /**
    * Get the value of HttpResponseCode / http_response_code.
    *
    * @return int
    */
    public function getHttpResponseCode()
    {
        $rtn = $this->data['http_response_code'];

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
    * Set the value of Url / url.
    *
    * @param $value string
    */
    public function setUrl($value)
    {
        $this->validateString('Url', $value);

        if ($this->data['url'] === $value) {
            return;
        }

        $this->data['url'] = $value;
        $this->setModified('url');
    }

    /**
    * Set the value of ParentUrl / parent_url.
    *
    * @param $value string
    */
    public function setParentUrl($value)
    {
        $this->validateString('ParentUrl', $value);

        if ($this->data['parent_url'] === $value) {
            return;
        }

        $this->data['parent_url'] = $value;
        $this->setModified('parent_url');
    }

    /**
    * Set the value of FirstScanEpoch / first_scan_epoch.
    *
    * @param $value int
    */
    public function setFirstScanEpoch($value)
    {
        $this->validateInt('FirstScanEpoch', $value);

        if ($this->data['first_scan_epoch'] === $value) {
            return;
        }

        $this->data['first_scan_epoch'] = $value;
        $this->setModified('first_scan_epoch');
    }

    /**
    * Set the value of LastScanEpoch / last_scan_epoch.
    *
    * @param $value int
    */
    public function setLastScanEpoch($value)
    {
        $this->validateInt('LastScanEpoch', $value);

        if ($this->data['last_scan_epoch'] === $value) {
            return;
        }

        $this->data['last_scan_epoch'] = $value;
        $this->setModified('last_scan_epoch');
    }

    /**
    * Set the value of HttpResponseCode / http_response_code.
    *
    * @param $value int
    */
    public function setHttpResponseCode($value)
    {
        $this->validateInt('HttpResponseCode', $value);

        if ($this->data['http_response_code'] === $value) {
            return;
        }

        $this->data['http_response_code'] = $value;
        $this->setModified('http_response_code');
    }
}
