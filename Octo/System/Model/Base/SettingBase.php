<?php

/**
 * Setting base model for table: setting
 */

namespace Octo\System\Model\Base;

use DateTime;
use Block8\Database\Query;
use Octo\Model;
use Octo\Store;

use Octo\System\Store\SettingStore;
use Octo\System\Model\Setting;

/**
 * Setting Base Model
 */
abstract class SettingBase extends Model
{
    protected $table = 'setting';
    protected $model = 'Setting';
    protected $data = [
        'id' => null,
        'key' => null,
        'value' => null,
        'scope' => null,
        'hidden' => 0,
    ];

    protected $getters = [
        'id' => 'getId',
        'key' => 'getKey',
        'value' => 'getValue',
        'scope' => 'getScope',
        'hidden' => 'getHidden',
    ];

    protected $setters = [
        'id' => 'setId',
        'key' => 'setKey',
        'value' => 'setValue',
        'scope' => 'setScope',
        'hidden' => 'setHidden',
    ];

    /**
     * Return the database store for this model.
     * @return SettingStore
     */
    public static function Store() : SettingStore
    {
        return SettingStore::load();
    }

    /**
     * Get Setting by primary key: id
     * @param int $id
     * @return Setting|null
     */
    public static function get(int $id) : ?Setting
    {
        return self::Store()->getById($id);
    }

    /**
     * @throws \Exception
     * @return Setting
     */
    public function save() : Setting
    {
        $rtn = self::Store()->save($this);

        if (empty($rtn)) {
            throw new \Exception('Failed to save Setting');
        }

        if (!($rtn instanceof Setting)) {
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
     * Get the value of Key / key
     * @return string
     */
     public function getKey() : string
     {
        $rtn = $this->data['key'];

        return $rtn;
     }
    
    /**
     * Get the value of Value / value
     * @return string
     */
     public function getValue() : ?string
     {
        $rtn = $this->data['value'];

        return $rtn;
     }
    
    /**
     * Get the value of Scope / scope
     * @return string
     */
     public function getScope() : string
     {
        $rtn = $this->data['scope'];

        return $rtn;
     }
    
    /**
     * Get the value of Hidden / hidden
     * @return int
     */
     public function getHidden() : ?int
     {
        $rtn = $this->data['hidden'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value int
     * @return Setting
     */
    public function setId(int $value) : Setting
    {

        if ($this->data['id'] !== $value) {
            $this->data['id'] = $value;
            $this->setModified('id');
        }

        return $this;
    }
    
    /**
     * Set the value of Key / key
     * @param $value string
     * @return Setting
     */
    public function setKey(string $value) : Setting
    {

        if ($this->data['key'] !== $value) {
            $this->data['key'] = $value;
            $this->setModified('key');
        }

        return $this;
    }
    
    /**
     * Set the value of Value / value
     * @param $value string
     * @return Setting
     */
    public function setValue(?string $value) : Setting
    {

        if ($this->data['value'] !== $value) {
            $this->data['value'] = $value;
            $this->setModified('value');
        }

        return $this;
    }
    
    /**
     * Set the value of Scope / scope
     * @param $value string
     * @return Setting
     */
    public function setScope(string $value) : Setting
    {

        if ($this->data['scope'] !== $value) {
            $this->data['scope'] = $value;
            $this->setModified('scope');
        }

        return $this;
    }
    
    /**
     * Set the value of Hidden / hidden
     * @param $value int
     * @return Setting
     */
    public function setHidden(?int $value) : Setting
    {

        if ($this->data['hidden'] !== $value) {
            $this->data['hidden'] = $value;
            $this->setModified('hidden');
        }

        return $this;
    }
    
}
