<?php

namespace Octo\Database;

use b8\Config;
use Octo\Model\Migration;
use Octo\Store;
use Octo\Store\MigrationStore;

class MigrationRunner
{
    /**
     * @var \Octo\Store\MigrationStore
     */
    protected $store;

    protected $isNewDb;

    public function __construct()
    {
        $this->store = Store::get('Migration');
    }

    public function runMigrations()
    {
        foreach ($this->getMigrationData() as $key => $migration) {
            if (!is_null($migration['migration'])) {
                continue;
            }

            try {
                $this->store->runMigration($migration);

                $obj = new Migration();
                $obj->setId((string)$key);
                $obj->setDateRun(new \DateTime());
                $this->store->saveByInsert($obj);
            } catch (\Exception $ex) {
                throw new \Exception('Failed to run migration: ' . $key, 0, $ex);
            }
        }
    }

    public function getMigrationData()
    {
        $migrations = $this->getMigrations(CMS_PATH . 'Database/Migrations/');

        $namespace = Config::getInstance()->get('site.namespace');
        $path = APP_PATH . $namespace . '/Database/Migrations/';

        if (is_dir($path)) {
            $migrations = array_merge($migrations, $this->getMigrations($path));
        }

        ksort($migrations);

        foreach ($migrations as $key => &$value) {
            $value['query_count'] = count($value['queries']);

            try {
                $value['migration'] = $this->store->getById($key);
            } catch (\Exception $ex) {
                $value['migration'] = null;
            }
        }

        return $migrations;
    }

    protected function getMigrations($path)
    {
        $directory = new \DirectoryIterator($path);

        $migrations = [];

        foreach ($directory as $file) {
            $queries = [];

            if ($file->isDot() || !$file->isFile()) {
                continue;
            }

            require_once($path . '/' . $file->getFilename());
            $migrations[$file->getBasename('.php')] = ['queries' => $queries];
        }

        return $migrations;
    }
}
