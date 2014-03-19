<?php

/**
 * FileDownload base model for table: file_download
 */

namespace Octo\System\Model\Base;

use b8\Store\Factory;

/**
 * FileDownload Base Model
 */
trait FileDownloadBase
{
    protected function init()
    {
        $this->tableName = 'file_download';
        $this->modelName = 'FileDownload';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['file_id'] = null;
        $this->getters['file_id'] = 'getFileId';
        $this->setters['file_id'] = 'setFileId';
        $this->data['downloaded'] = null;
        $this->getters['downloaded'] = 'getDownloaded';
        $this->setters['downloaded'] = 'setDownloaded';

        // Foreign keys:
        $this->getters['File'] = 'getFile';
        $this->setters['File'] = 'setFile';
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
    * Get the value of FileId / file_id.
    *
    * @return string
    */
    public function getFileId()
    {
        $rtn = $this->data['file_id'];

        return $rtn;
    }

    /**
    * Get the value of Downloaded / downloaded.
    *
    * @return \DateTime
    */
    public function getDownloaded()
    {
        $rtn = $this->data['downloaded'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

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
    * Set the value of FileId / file_id.
    *
    * @param $value string
    */
    public function setFileId($value)
    {
        $this->validateString('FileId', $value);

        if ($this->data['file_id'] === $value) {
            return;
        }

        $this->data['file_id'] = $value;
        $this->setModified('file_id');
    }

    /**
    * Set the value of Downloaded / downloaded.
    *
    * @param $value \DateTime
    */
    public function setDownloaded($value)
    {
        $this->validateDate('Downloaded', $value);

        if ($this->data['downloaded'] === $value) {
            return;
        }

        $this->data['downloaded'] = $value;
        $this->setModified('downloaded');
    }

    /**
    * Get the File model for this FileDownload by Id.
    *
    * @uses \Octo\System\Store\FileStore::getById()
    * @uses \Octo\System\Model\File
    * @return \Octo\System\Model\File
    */
    public function getFile()
    {
        $key = $this->getFileId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('File', 'Octo')->getById($key);
    }

    /**
    * Set File - Accepts an ID, an array representing a File or a File model.
    *
    * @param $value mixed
    */
    public function setFile($value)
    {
        // Is this an instance of File?
        if ($value instanceof \Octo\System\Model\File) {
            return $this->setFileObject($value);
        }

        // Is this an array representing a File item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setFileId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setFileId($value);
    }

    /**
    * Set File - Accepts a File model.
    *
    * @param $value \Octo\System\Model\File
    */
    public function setFileObject(\Octo\System\Model\File $value)
    {
        return $this->setFileId($value->getId());
    }
}
