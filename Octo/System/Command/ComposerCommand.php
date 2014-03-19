<?php

namespace Octo\Command;

class ComposerCommand
{
    public function runMigrations()
    {
        $binPath = realpath(dirname(__FILE__) . '/../../octocmd');

        passthru($binPath . ' db:migration');
        passthru($binPath . ' db:generate');
    }
}