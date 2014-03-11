<?php

/**
 * File model for table: file
 */

namespace Octo\Model;

use b8\Config;
use Octo\Store;
use Octo\Model\Base\FileBase;

/**
 * File Model
 * @uses Octo\Model\Base\FileBase
 */
class File extends FileBase
{

    public function __construct($initialData = array())
    {
        parent::__construct($initialData);

        $this->categoryStore = Store::get('Category');

        $this->getters['url'] = 'getUrl';
        $this->getters['path'] = 'getPath';
        $this->getters['formatted_size'] = 'getFormattedSize';
    }

    /**
     * Get the absolute path to the image
     *
     * @return string
     * @author James Inman
     */
    public function getPath()
    {
        return APP_PATH . 'public/uploads/' . strtolower($this->getId() . '.' . $this->getExtension());
    }

    /**
     * Get the URL to the image
     *
     * @return string
     * @author James Inman
     */
    public function getUrl()
    {
        $path = '/uploads/' . strtolower($this->getId() . '.' . $this->getExtension());
        return Config::getInstance()->get('site.url') . $path;
    }

    /**
     * Get the URL to the image
     *
     * @return string
     * @author James Inman
     */
    public function getFormattedSize()
    {
        $size = $this->getSize();
        if ($size >= 1073741824) {
            $fileSize = round($size / 1024 / 1024 / 1024, 1) . 'GB';
        } elseif ($size >= 1048576) {
            $fileSize = round($size / 1024 / 1024, 1) . 'MB';
        } elseif($size >= 1024) {
            $fileSize = round($size / 1024, 1) . 'KB';
        } else {
            $fileSize = $size . 'B';
        }
        return $fileSize;
    }

    /**
    * Set the value of Scope / scope.
    *
    * @param $value string
    */
    public function setUrl($value)
    {
        $this->data['url'] = $value;

        $this->_setModified('url');
    }
}
