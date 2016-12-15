<?php

use Phinx\Migration\AbstractMigration;

class JobsMessageField extends AbstractMigration
{
    public function change()
    {
        $job = $this->table('job');
        $job->changeColumn('message', 'string', ['length' => 1000, 'null' => true, 'default' => null]);
        $job->save();
    }
}
