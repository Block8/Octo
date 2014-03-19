<?php

namespace Octo\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Octo\Utilities\TwitterUtilities;

/**
 * Get recent tweets posted by the authenticated user
 *
 * Class GetUserTweetsCommand
 *
 * @todo Avoid twitter rate limits
 */
class GetStreamTweetsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('tweets:get_stream')
            ->setDescription('Retrieves and caches tweets for a given search term defined in the application settings.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            TwitterUtilities::getStream();
        } catch (\TwitterException $e) {
             print $e->getMessage();
        }
    }
}