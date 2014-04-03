<?php

/**
 * ItemFile base model for table: item_file
 */

namespace Octo\Shop\Model\Base;

use b8\Store\Factory;

/**
 * ItemFile Base Model
 */
trait ItemFileBase
{
    protected function init()
    {
        $this->tableName = 'item_file';
        $this->modelName = 'ItemFile';

        // Columns:
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        $this->data['item_id'] = null;
        $this->getters['item_id'] = 'getItemId';
        $this->setters['item_id'] = 'setItemId';
        $this->data['file_id'] = null;
        $this->getters['file_id'] = 'getFileId';
        $this->setters['file_id'] = 'setFileId';
        $this->data['position'] = null;
        $this->getters['position'] = 'getPosition';
        $this->setters['position'] = 'setPosition';

        // Foreign keys:
        $this->getters['Item'] = 'getItem';
        $this->setters['Item'] = 'setItem';
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
    * Get the value of ItemId / item_id.
    *
    * @return int
    */
    public function getItemId()
    {
        $rtn = $this->data['item_id'];

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
    * Get the value of Position / position.
    *
    * @return int
    */
    public function getPosition()
    {
        $rtn = $this->data['position'];

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
    * Set the value of ItemId / item_id.
    *
    * Must not be null.
    * @param $value int
    */
    public function setItemId($value)
    {
        $this->validateNotNull('ItemId', $value);
        $this->validateInt('ItemId', $value);

        if ($this->data['item_id'] === $value) {
            return;
        }

        $this->data['item_id'] = $value;
        $this->setModified('item_id');
    }

    /**
    * Set the value of FileId / file_id.
    *
    * Must not be null.
    * @param $value string
    */
    public function setFileId($value)
    {
        $this->validateNotNull('FileId', $value);
        $this->validateString('FileId', $value);

        if ($this->data['file_id'] === $value) {
            return;
        }

        $this->data['file_id'] = $value;
        $this->setModified('file_id');
    }

    /**
    * Set the value of Position / position.
    *
    * @param $value int
    */
    public function setPosition($value)
    {
        $this->validateInt('Position', $value);

        if ($this->data['position'] === $value) {
            return;
        }

        $this->data['position'] = $value;
        $this->setModified('position');
    }

    /**
    * Get the Item model for this ItemFile by Id.
    *
    * @uses \Octo\Invoicing\Store\ItemStore::getById()
    * @uses \Octo\Invoicing\Model\Item
    * @return \Octo\Invoicing\Model\Item
    */
    public function getItem()
    {
        $key = $this->getItemId();

        if (empty($key)) {
            return null;
        }

        return Factory::getStore('Item', 'Octo\Invoicing')->getById($key);
    }

    /**
    * Set Item - Accepts an ID, an array representing a Item or a Item model.
    *
    * @param $value mixed
    */
    public function setItem($value)
    {
        // Is this an instance of Item?
        if ($value instanceof \Octo\Invoicing\Model\Item) {
            return $this->setItemObject($value);
        }

        // Is this an array representing a Item item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setItemId($value['id']);
        }

        // Is this a scalar value representing the ID of this foreign key?
        return $this->setItemId($value);
    }

    /**
    * Set Item - Accepts a Item model.
    *
    * @param $value \Octo\Invoicing\Model\Item
    */
    public function setItemObject(\Octo\Invoicing\Model\Item $value)
    {
        return $this->setItemId($value->getId());
    }
    /**
    * Get the File model for this ItemFile by Id.
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

        return Factory::getStore('File', 'Octo\System')->getById($key);
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
