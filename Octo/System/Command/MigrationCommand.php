<?php

namespace Octo\System\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use b8\Database;
use Octo\Database\MigrationRunner;

class MigrationCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('db:migration')
            ->setDescription('Update the database using migration scripts.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $mr = new MigrationRunner();
            $mr->runMigrations();
        } catch (\Exception $ex) {
            print $ex->getMessage() . PHP_EOL;
        }
    }
}