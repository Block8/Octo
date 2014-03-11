<?php

namespace Octo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GitPushCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('git:push')
            ->setDescription('Update system from remote git repositories (Gitlab)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        passthru('git push origin master');
    }
}