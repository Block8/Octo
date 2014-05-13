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
        if ($_SESSION['user']->getIsAdmin() && !Config::getInstance()->get('b8.hide_database')) {
            $menu->addRoot('Database', '/database')->setIcon('cogs');
        }
    }

    public function init()
    {
        if (!$_SESSION['user']->getIsAdmin()) {
            $this->response->setHeader('Location', '/' . $this->config->get('site.admin_uri'));
        }
    }

    public function index()
    {
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

        $this->response = new \b8\Http\Response\RedirectResponse();
        $this->response->setHeader('Location', '/'.$this->config->get('site.admin_uri').'/database');
    }

    public function markAsRun()
    {
        $runner = new MigrationRunner();
        $runner->markAllAsRun();
        $this->successMessage('All database migrations ran successfully.', true);

        $this->response = new \b8\Http\Response\RedirectResponse();
        $this->response->setHeader('Location', '/'.$this->config->get('site.admin_uri').'/database');
    }
}
