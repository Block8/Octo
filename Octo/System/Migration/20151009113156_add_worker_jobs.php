<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class AddWorkerJobs extends AbstractMigration
{
    public function change()
    {
        $job = $this->table('job');

        $job->addColumn('type', 'string', ['limit' => 100]);
        $job->addColumn('status', 'integer', ['length' => MysqlAdapter::INT_TINY, 'null' => false, 'default' => 0]);
        $job->addColumn('date_created', 'datetime');
        $job->addColumn('date_updated', 'datetime');
        $job->addColumn('data', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR]);
        $job->addColumn('message', 'string', ['limit' => 250]);
        $job->addColumn('queue_id', 'integer');
        $job->addIndex('status', ['unique' => false]);
        $job->addIndex('type', ['unique' => false]);

        $job->create();


        $job = $this->table('scheduled_job');
        $job->addColumn('type', 'string', ['limit' => 100]);
        $job->addColumn('data', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR]);
        $job->addColumn('frequency', 'integer');
        $job->addColumn('current_job_id', 'integer', ['null' => true, 'signed' => true]);

        $job->addForeignKey('current_job_id', 'job', 'id', ['delete' => 'RESTRICT', 'update' => 'CASCADE']);
        $job->create();
    }
}
