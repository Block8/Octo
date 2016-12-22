<?php

/**
 * Setting base model for table: setting
 */

namespace Octo\System\Model\Base;

use DateTime;
use Block8\Database\Query;
use Octo\Model;
use Octo\Store;
use Octo\System\Model\Setting;

/**
 * Setting Base Model
 */
abstract class SettingBase extends Model
{
    protected function init()
    {
        $this->table = 'setting';
        $this->model = 'Setting';

        // Columns:
        
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        
        $this->data['key'] = null;
        $this->getters['key'] = 'getKey';
        $this->setters['key'] = 'setKey';
        
        $this->data['value'] = null;
        $this->getters['value'] = 'getValue';
        $this->setters['value'] = 'setValue';
        
        $this->data['scope'] = null;
        $this->getters['scope'] = 'getScope';
        $this->setters['scope'] = 'setScope';
        
        $this->data['hidden'] = null;
        $this->getters['hidden'] = 'getHidden';
        $this->setters['hidden'] = 'setHidden';
        
        // Foreign keys:
        
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
