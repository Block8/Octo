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
use Octo\System\Store\ContentItemStore;

/**
 * ContentItem Base Model
 */
abstract class ContentItemBase extends Model
{
    protected $table = 'content_item';
    protected $model = 'ContentItem';
    protected $data = [
        'id' => null,
        'content' => null,
    ];

    protected $getters = [
        'id' => 'getId',
        'content' => 'getContent',
    ];

    protected $setters = [
        'id' => 'setId',
        'content' => 'setContent',
    ];

    /**
     * Return the database store for this model.
     * @return ContentItemStore
     */
    public static function Store() : ContentItemStore
    {
        return ContentItemStore::load();
    }

    /**
     * Get ContentItem by primary key: id
     * @param string $id
     * @return ContentItem|null
     */
    public static function get(string $id) : ?ContentItem
    {
        return self::Store()->getById($id);
    }

    /**
     * @throws \Exception
     * @return ContentItem
     */
    public function save() : ContentItem
    {
        $rtn = self::Store()->save($this);

        if (empty($rtn)) {
            throw new \Exception('Failed to save ContentItem');
        }

        if (!($rtn instanceof ContentItem)) {
            throw new \Exception('Unexpected ' . get_class($rtn) . ' received from save.');
        }

        $this->data = $rtn->toArray();

        return $this;
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
