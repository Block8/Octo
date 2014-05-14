<?php

namespace Octo\System\Command;

use b8\Config;
use b8\Database;
use Octo\System\CommandBase;

class GenerateCommand extends CommandBase
{
    protected function configure()
    {
        $this
            ->setName('db:generate')
            ->setDescription('Generate models and stores from the database tables.');
    }

    protected function execute()
    {
        $connection = Database::getConnection();
        $namespaces = Config::getInstance()->get('app.namespaces');

        $paths = Config::getInstance()->get('Octo.paths.namespaces');
        $gen = new Database\CodeGenerator($connection, $namespaces, $paths, true);
        $gen->generateModels();
        $gen->generateStores();

        // Removed May 7 2014, as we're not using the functionality.
        // $controllerGenerator = new ControllerGenerator($connection, $namespaces, $paths);
        // $controllerGenerator->generateControllers();
    }
}
