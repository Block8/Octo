<?php

namespace Octo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Get recent tweets posted by the authenticated user
 *
 * Class GetUserTweetsCommand
 *
 * @todo Avoid twitter rate limits
 */
class UpdateAnalyticsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('analytics:update')
            ->setDescription('Update and cache Google Analytics.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
