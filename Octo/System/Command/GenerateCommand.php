<?php

namespace Octo\System\Command;

use b8\Config;
use Block8\Database\Connection;
use Block8\Database\Mapper;
use Octo\Database\CodeGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('db:generate')
            ->setDescription('Generate models and stores from the database tables.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        unset($input, $output);

        $c = Config::getInstance();
        
        $connection = Connection::get();

        $namespaces = $c->get('app.namespaces');
        $paths = $c->get('Octo.paths.namespaces');

        $mapper = new Mapper($connection);

        $gen = new CodeGenerator($mapper, $namespaces, $paths);
        $gen->generateModels();
        $gen->generateStores();
    }
}
