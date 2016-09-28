<?php

/**
 * ContentItem base model for table: content_item
 */

namespace Octo\System\Model\Base;

use DateTime;
use Octo\Model;
use Octo\Store;

/**
 * ContentItem Base Model
 */
class ContentItemBase extends Model
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

     public function getId()
     {
        $rtn = $this->data['id'];

        return $rtn;
     }
    
    /**
     * Get the value of Content / content
     * @return array|null
     */

     public function getContent()
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
     */
    public function setId(string $value)
    {

        $this->validateNotNull('Id', $value);

        if ($this->data['id'] === $value) {
            return;
        }

        $this->data['id'] = $value;
        $this->setModified('id');
    }
    
    /**
     * Set the value of Content / content
     * @param $value array|null
     */
    public function setContent($value)
    {
        $this->validateJson($value);
        $this->validateNotNull('Content', $value);

        if ($this->data['content'] === $value) {
            return;
        }

        $this->data['content'] = $value;
        $this->setModified('content');
    }
    
    
    public function PageVersions()
    {
        return Store::get('PageVersion')->where('content_item_id', $this->data['id']);
    }
}
