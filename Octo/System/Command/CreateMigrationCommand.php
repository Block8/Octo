<?php

namespace Octo\System\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use b8\Database;

class CreateMigrationCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('db:create-migration')
            ->setDescription('Create a new migration script.')
            ->addArgument('name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $name = str_replace(' ', '', $name);

        passthru(APP_PATH . 'vendor/bin/phinx create -c "' . CMS_PATH . 'phinx.php" ' . $name);
    }
}
