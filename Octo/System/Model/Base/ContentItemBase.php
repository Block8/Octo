<?php

/**
 * ContentItem base model for table: content_item
 */

namespace Octo\System\Model\Base;

use DateTime;
use Block8\Database\Query;
use Octo\Model;
use Octo\Store;
use Octo\System\Model\ContentItem;

/**
 * ContentItem Base Model
 */
abstract class ContentItemBase extends Model
{
    protected function init()
    {
        $this->table = 'content_item';
        $this->model = 'ContentItem';

        // Columns:
        
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        
        $this->data['content'] = null;
        $this->getters['content'] = 'getContent';
        $this->setters['content'] = 'setContent';
        
        // Foreign keys:
        
    }

    
    /**
     * Get the value of Id / id
     * @return string
     */

     public function getId() : string
     {
        $rtn = $this->data['id'];

        return $rtn;
     }
    
    /**
     * Get the value of Content / content
     * @return array
     */

     public function getContent() : ?array
     {
        $rtn = $this->data['content'];

        $rtn = json_decode($rtn, true);

        if ($rtn === false) {
            $rtn = null;
        }

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value string
     * @return ContentItem
     */
    public function setId(string $value) : ContentItem
    {

        if ($this->data['id'] !== $value) {
            $this->data['id'] = $value;
            $this->setModified('id');
        }

        return $this;
    }
    
    /**
     * Set the value of Content / content
     * @param $value array
     * @return ContentItem
     */
    public function setContent($value) : ContentItem
    {
        $this->validateJson($value);

        if ($this->data['content'] !== $value) {
            $this->data['content'] = $value;
            $this->setModified('content');
        }

        return $this;
    }
    
    
}
