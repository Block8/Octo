<?php

namespace Octo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use b8\Config;
use b8\Database;

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
        $namespaces = Config::getInstance()->get('Octo.namespaces', ['default' => 'Octo']);
        $paths = ['default' => APP_PATH, 'Octo' => CMS_BASE_PATH];
        $gen = new Database\CodeGenerator($connection, $namespaces, $paths, true);
        $gen->generateModels();
        $gen->generateStores();
    }
}