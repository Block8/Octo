<?php

/**
 * File model for table: file
 */

namespace Octo\System\Model;

use b8\Config;
use Octo\Http\Upload;
use Octo\Store;
use Octo;

/**
 * File Model
 * @uses Octo\System\Model\Base\FileBaseBase
 */
class File extends Octo\Model
{
    use Base\FileBase;

    protected $hash = '';

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
        } elseif ($size >= 1024) {
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

    /**
     * @param array $info Return value from b8\Upload->getFileInfo()
     */
    public function setInfo(array $info)
    {
        if ($this->getScope() == null || $this->getScope() == '') {
            throw new \Exception("You must set the scope before calling setInfo().");
        }

        $this->hash = md5($info['hash'] . $this->getScope());
        $this->setId(strtolower($this->hash));
        $this->setFilename(strtolower($info['basename']));
        $this->setTitle(strtolower($info['basename']));
        $this->setExtension(strtolower($info['extension']));
        $this->setMimeType($info['type']);
        $this->setSize($info['size']);
        $this->setCreatedDate(new \DateTime);
        $this->setUpdatedDate(new \DateTime);
    }

    /**
     * Upload a file
     *
     * @param $field
     * @param $scope
     * @param null $uploadDirectory
     * @return null|File
     */
    public static function upload($field, $scope, $uploadDirectory = null)
    {
        if ($uploadDirectory == null) {
            $uploadDirectory = APP_PATH . 'public/uploads/';
        }

        try {
            $upload = new Upload($field);
        } catch (\Exception $e) {
            // No file uploaded
            return null;
        }

        $files = [];
        foreach ($upload->getFiles() as $file) {
            print 'Setting info: ';
            $files[] = static::setSingleFileInfo($file, $scope, $uploadDirectory);
        }

        return $files;
    }

    protected static function setSingleFileInfo($upload, $scope, $uploadDirectory)
    {
        $info = $upload->getFileInfo();
        $newHash = strtolower(md5($info['hash'] . $scope));

        $fileStore = Store::get('File');
        if ($file = $fileStore->getById(strtolower(md5($info['hash'] . $scope)))) {
            return $file;
        }

        $upload->copyTo($uploadDirectory . $newHash . '.' . $info['extension']);

        $file = new File;
        $file->setScope($scope);
        $file->setInfo($info);
        $file->setUserId($_SESSION['user']->getId());
        $file = $fileStore->saveByInsert($file);
        return $file;
    }
}
