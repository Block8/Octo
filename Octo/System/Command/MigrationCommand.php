<?php

namespace Octo\System\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use b8\Database;

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
        unset($input);

        passthru(APP_PATH . 'vendor/bin/phinx migrate -c "' . CMS_PATH . 'phinx.php"');
    }
}
