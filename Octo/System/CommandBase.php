<?php

namespace Octo\System;

use Symfony\Component\Console\Command\Command;
use Octo;

class CommandBase extends Command
{
    protected function getName()
    {
        return 'System';
    }

    protected function execute()
    {
        parent::execute();
    }
}
