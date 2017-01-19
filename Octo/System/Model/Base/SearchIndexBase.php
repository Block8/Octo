<?php

/**
 * SearchIndex base model for table: search_index
 */

namespace Octo\System\Model\Base;

use DateTime;
use Block8\Database\Query;
use Octo\Model;
use Octo\Store;

use Octo\System\Store\SearchIndexStore;
use Octo\System\Model\SearchIndex;

/**
 * SearchIndex Base Model
 */
abstract class SearchIndexBase extends Model
{
    protected $table = 'search_index';
    protected $model = 'SearchIndex';
    protected $data = [
        'id' => null,
        'word' => null,
        'model' => null,
        'content_id' => null,
        'instances' => 1,
    ];

    protected $getters = [
        'id' => 'getId',
        'word' => 'getWord',
        'model' => 'getModel',
        'content_id' => 'getContentId',
        'instances' => 'getInstances',
    ];

    protected $setters = [
        'id' => 'setId',
        'word' => 'setWord',
        'model' => 'setModel',
        'content_id' => 'setContentId',
        'instances' => 'setInstances',
    ];

    /**
     * Return the database store for this model.
     * @return SearchIndexStore
     */
    public static function Store() : SearchIndexStore
    {
        return SearchIndexStore::load();
    }

    /**
     * Get SearchIndex by primary key: id
     * @param int $id
     * @return SearchIndex|null
     */
    public static function get(int $id) : ?SearchIndex
    {
        return self::Store()->getById($id);
    }

    /**
     * @throws \Exception
     * @return SearchIndex
     */
    public function save() : SearchIndex
    {
        $rtn = self::Store()->save($this);

        if (empty($rtn)) {
            throw new \Exception('Failed to save SearchIndex');
        }

        if (!($rtn instanceof SearchIndex)) {
            throw new \Exception('Unexpected ' . get_class($rtn) . ' received from save.');
        }

        $this->data = $rtn->toArray();

        return $this;
    }


    /**
     * Get the value of Id / id
     * @return int
     */
     public function getId() : int
     {
        $rtn = $this->data['id'];

        return $rtn;
     }
    
    /**
     * Get the value of Word / word
     * @return string
     */
     public function getWord() : string
     {
        $rtn = $this->data['word'];

        return $rtn;
     }
    
    /**
     * Get the value of Model / model
     * @return string
     */
     public function getModel() : string
     {
        $rtn = $this->data['model'];

        return $rtn;
     }
    
    /**
     * Get the value of ContentId / content_id
     * @return string
     */
     public function getContentId() : string
     {
        $rtn = $this->data['content_id'];

        return $rtn;
     }
    
    /**
     * Get the value of Instances / instances
     * @return int
     */
     public function getInstances() : int
     {
        $rtn = $this->data['instances'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value int
     * @return SearchIndex
     */
    public function setId(int $value) : SearchIndex
    {

        if ($this->data['id'] !== $value) {
            $this->data['id'] = $value;
            $this->setModified('id');
        }

        return $this;
    }
    
    /**
     * Set the value of Word / word
     * @param $value string
     * @return SearchIndex
     */
    public function setWord(string $value) : SearchIndex
    {

        if ($this->data['word'] !== $value) {
            $this->data['word'] = $value;
            $this->setModified('word');
        }

        return $this;
    }
    
    /**
     * Set the value of Model / model
     * @param $value string
     * @return SearchIndex
     */
    public function setModel(string $value) : SearchIndex
    {

        if ($this->data['model'] !== $value) {
            $this->data['model'] = $value;
            $this->setModified('model');
        }

        return $this;
    }
    
    /**
     * Set the value of ContentId / content_id
     * @param $value string
     * @return SearchIndex
     */
    public function setContentId(string $value) : SearchIndex
    {

        if ($this->data['content_id'] !== $value) {
            $this->data['content_id'] = $value;
            $this->setModified('content_id');
        }

        return $this;
    }
    
    /**
     * Set the value of Instances / instances
     * @param $value int
     * @return SearchIndex
     */
    public function setInstances(int $value) : SearchIndex
    {

        if ($this->data['instances'] !== $value) {
            $this->data['instances'] = $value;
            $this->setModified('instances');
        }

        return $this;
    }
    
}
