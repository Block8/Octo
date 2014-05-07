<?php

namespace Octo\System\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use b8\Config;
use b8\Database;
use Octo\Database\ControllerGenerator;

class GenerateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('db:generate')
            ->setDescription('Generate models and stores from the database tables.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
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
