<?php

use Phinx\Migration\AbstractMigration;

class FixJobQueueTable extends AbstractMigration
{
    public function change()
    {
        $job = $this->table('job');
        $job->changeColumn('queue_id', 'integer', ['null' => true, 'default' => null]);
        $job->save();
    }
}
