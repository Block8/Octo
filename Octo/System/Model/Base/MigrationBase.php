<?php

/**
 * Migration base model for table: migration
 */

namespace Octo\System\Model\Base;

use DateTime;
use Octo\Model;
use Octo\Store;

/**
 * Migration Base Model
 */
class MigrationBase extends Model
{
    protected function init()
    {
        $this->table = 'migration';
        $this->model = 'Migration';

        // Columns:
        
        $this->data['version'] = null;
        $this->getters['version'] = 'getVersion';
        $this->setters['version'] = 'setVersion';
        
        $this->data['start_time'] = null;
        $this->getters['start_time'] = 'getStartTime';
        $this->setters['start_time'] = 'setStartTime';
        
        $this->data['end_time'] = null;
        $this->getters['end_time'] = 'getEndTime';
        $this->setters['end_time'] = 'setEndTime';
        
        // Foreign keys:
        
    }

    
    /**
     * Get the value of Version / version
     * @return int
     */

     public function getVersion()
     {
        $rtn = $this->data['version'];

        return $rtn;
     }
    
    /**
     * Get the value of StartTime / start_time
     * @return string
     */

     public function getStartTime()
     {
        $rtn = $this->data['start_time'];

        return $rtn;
     }
    
    /**
     * Get the value of EndTime / end_time
     * @return string
     */

     public function getEndTime()
     {
        $rtn = $this->data['end_time'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Version / version
     * @param $value int
     */
    public function setVersion(int $value)
    {

        $this->validateNotNull('Version', $value);

        if ($this->data['version'] === $value) {
            return;
        }

        $this->data['version'] = $value;
        $this->setModified('version');
    }
    
    /**
     * Set the value of StartTime / start_time
     * @param $value string
     */
    public function setStartTime(string $value)
    {

        $this->validateNotNull('StartTime', $value);

        if ($this->data['start_time'] === $value) {
            return;
        }

        $this->data['start_time'] = $value;
        $this->setModified('start_time');
    }
    
    /**
     * Set the value of EndTime / end_time
     * @param $value string
     */
    public function setEndTime(string $value)
    {

        $this->validateNotNull('EndTime', $value);

        if ($this->data['end_time'] === $value) {
            return;
        }

        $this->data['end_time'] = $value;
        $this->setModified('end_time');
    }
    
    }
