<?php

/**
 * Migration base model for table: migration
 */

namespace Octo\System\Model\Base;

use DateTime;
use Block8\Database\Query;
use Octo\Model;
use Octo\Store;
use Octo\System\Model\Migration;

/**
 * Migration Base Model
 */
abstract class MigrationBase extends Model
{
    protected function init()
    {
        $this->table = 'migration';
        $this->model = 'Migration';

        // Columns:
        
        $this->data['version'] = null;
        $this->getters['version'] = 'getVersion';
        $this->setters['version'] = 'setVersion';
        
        $this->data['migration_name'] = null;
        $this->getters['migration_name'] = 'getMigrationName';
        $this->setters['migration_name'] = 'setMigrationName';
        
        $this->data['start_time'] = null;
        $this->getters['start_time'] = 'getStartTime';
        $this->setters['start_time'] = 'setStartTime';
        
        $this->data['end_time'] = null;
        $this->getters['end_time'] = 'getEndTime';
        $this->setters['end_time'] = 'setEndTime';
        
        $this->data['breakpoint'] = null;
        $this->getters['breakpoint'] = 'getBreakpoint';
        $this->setters['breakpoint'] = 'setBreakpoint';
        
        // Foreign keys:
        
    }

    
    /**
     * Get the value of Version / version
     * @return int
     */

     public function getVersion() : int
     {
        $rtn = $this->data['version'];

        return $rtn;
     }
    
    /**
     * Get the value of MigrationName / migration_name
     * @return string
     */

     public function getMigrationName() : ?string
     {
        $rtn = $this->data['migration_name'];

        return $rtn;
     }
    
    /**
     * Get the value of StartTime / start_time
     * @return string
     */

     public function getStartTime() : string
     {
        $rtn = $this->data['start_time'];

        return $rtn;
     }
    
    /**
     * Get the value of EndTime / end_time
     * @return string
     */

     public function getEndTime() : ?string
     {
        $rtn = $this->data['end_time'];

        return $rtn;
     }
    
    /**
     * Get the value of Breakpoint / breakpoint
     * @return int
     */

     public function getBreakpoint() : int
     {
        $rtn = $this->data['breakpoint'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Version / version
     * @param $value int
     * @return Migration
     */
    public function setVersion(int $value) : Migration
    {

        if ($this->data['version'] !== $value) {
            $this->data['version'] = $value;
            $this->setModified('version');
        }

        return $this;
    }
    
    /**
     * Set the value of MigrationName / migration_name
     * @param $value string
     * @return Migration
     */
    public function setMigrationName(?string $value) : Migration
    {

        if ($this->data['migration_name'] !== $value) {
            $this->data['migration_name'] = $value;
            $this->setModified('migration_name');
        }

        return $this;
    }
    
    /**
     * Set the value of StartTime / start_time
     * @param $value string
     * @return Migration
     */
    public function setStartTime(string $value) : Migration
    {

        if ($this->data['start_time'] !== $value) {
            $this->data['start_time'] = $value;
            $this->setModified('start_time');
        }

        return $this;
    }
    
    /**
     * Set the value of EndTime / end_time
     * @param $value string
     * @return Migration
     */
    public function setEndTime(?string $value) : Migration
    {

        if ($this->data['end_time'] !== $value) {
            $this->data['end_time'] = $value;
            $this->setModified('end_time');
        }

        return $this;
    }
    
    /**
     * Set the value of Breakpoint / breakpoint
     * @param $value int
     * @return Migration
     */
    public function setBreakpoint(int $value) : Migration
    {

        if ($this->data['breakpoint'] !== $value) {
            $this->data['breakpoint'] = $value;
            $this->setModified('breakpoint');
        }

        return $this;
    }
    
    
}
