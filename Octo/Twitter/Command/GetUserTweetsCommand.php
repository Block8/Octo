<?php

namespace Octo\Twitter\Command;

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
class GetUserTweetsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('tweets:get_user')
            ->setDescription('Retrieves and caches tweets according to the application\'s twitter settings.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        unset($input, $output);

        try {
            TwitterUtilities::getUser();
        } catch (\TwitterException $e) {
             print $e->getMessage();
        }
    }
}
