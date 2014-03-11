<?php

namespace Octo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GitPullCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('git:pull')
            ->setDescription('Update system from remote git repositories (Gitlab)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        passthru('git pull origin master');
    }
}