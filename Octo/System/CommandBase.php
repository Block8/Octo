<?php

namespace Octo\System;

use Symfony\Component\Console\Command\Command;
use Octo;

class CommandBase extends Command
{
    public function getName()
    {
        return 'System';
    }

    public function execute($input, $output)
    {
        static::run();
    }
}
