<?php

/**
 * Migration base store for table: migration

 */

namespace Octo\System\Store\Base;

use Octo\Store;
use Octo\System\Model\Migration;
use Octo\System\Model\MigrationCollection;

/**
 * Migration Base Store
 */
class MigrationStoreBase extends Store
{
    protected $table = 'migration';
    protected $model = 'Octo\System\Model\Migration';
    protected $key = '';

}
