<?php

namespace Octo\System\Admin\Controller;

use b8\Config;
use Octo\Admin\Controller;
use Octo\Admin\Menu;
use Octo\Database\MigrationRunner;
use Octo\System\Model\Migration;

class DatabaseController extends Controller
{
    public static function registerMenus(Menu $menu)
    {
        $dev = $menu->getRoot('Developer');

        if (!$dev) {
            $dev = $menu->addRoot('Developer', '/developer', false)->setIcon('cogs');
        }

        $database = new Menu\Item('Database Migrations', '/database');
        $database->addChild(new Menu\Item('Run', '/database/run-migrations', true));
        $database->addChild(new Menu\Item('Mark as Run', '/database/mark-as-run', true));
        $dev->addChild($database);
    }

    public function init()
    {
        if (!$_SESSION['user']->getIsAdmin()) {
            $this->redirect('/');
        }
    }

    public function index()
    {
        $this->setTitle('Database Manager');

        $runner = new MigrationRunner();
        $migrationData = $runner->getMigrationData();
        krsort($migrationData);
        $this->view->migrations = $migrationData;

        $migrationsToRun = 0;
        $queriesToRun = 0;

        foreach ($migrationData as $value) {
            if (is_null($value['migration'])) {
                $migrationsToRun++;
                $queriesToRun += $value['query_count'];
            }
        }

        $this->view->toRun = ['queries' => $queriesToRun, 'migrations' => $migrationsToRun];
    }

    public function runMigrations()
    {
        $errors = false;

        try {
            $runner = new MigrationRunner();
            $runner->runMigrations();
        } catch (\Exception $ex) {
            $errors = true;
            $this->errorMessage($ex->getMessage(), true);
        }

        if (!$errors) {
            $this->successMessage('All database migrations ran successfully.', true);
        }

        $this->redirect('/database');
    }

    public function markAsRun()
    {
        $runner = new MigrationRunner();
        $runner->markAllAsRun();
        $this->successMessage('All database migrations ran successfully.', true);

        $this->redirect('/database');
    }
}
