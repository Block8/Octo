<?php

use Phinx\Migration\AbstractMigration;

class JsonColumnType extends AbstractMigration
{
    public function up()
    {
        $this->execute("UPDATE content_item SET content = '{}' WHERE content = '' OR content IS NULL");
        $this->table('content_item')
            ->changeColumn('content', \Phinx\Db\Adapter\AdapterInterface::PHINX_TYPE_JSON, ['null' => false])
            ->save();


        $this->execute("UPDATE job SET `data` = '{}' WHERE `data` = '' OR `data` IS NULL");
        $this->table('job')
            ->changeColumn('data', \Phinx\Db\Adapter\AdapterInterface::PHINX_TYPE_JSON, ['null' => false])
            ->save();


        $this->execute("UPDATE scheduled_job SET `data` = '{}' WHERE `data` = '' OR `data` IS NULL");
        $this->table('scheduled_job')
            ->changeColumn('data', \Phinx\Db\Adapter\AdapterInterface::PHINX_TYPE_JSON, ['null' => false])
            ->save();
    }
}
