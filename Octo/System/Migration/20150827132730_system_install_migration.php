<?php

use \Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class SystemInstallMigration extends AbstractMigration
{
    public function up()
    {
        // Create tables:
        $this->createContact();
        $this->createContentItem();
        $this->createLog();
        $this->createPermission();
        $this->createSearchIndex();
        $this->createSetting();
        $this->createUser();

        // Add foreign keys:
        $table = $this->table('log');

        if (!$table->hasForeignKey('user_id')) {
            $table->addForeignKey('user_id', 'user', 'id', ['delete' => 'SET_NULL', 'update' => 'CASCADE']);
            $table->save();
        }

        $table = $this->table('permission');

        if (!$table->hasForeignKey('user_id')) {
            $table->addForeignKey('user_id', 'user', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE']);
            $table->save();
        }
    }

    protected function createContact()
    {
        $table = $this->table('contact', ['id' => false, 'primary_key' => ['id']]);

        if (!$this->hasTable('contact')) {
            $table->addColumn('id', 'integer', ['signed' => false]);
            $table->create();
        }

        if (!$table->hasColumn('email')) {
            $table->addColumn('email', 'string', ['limit' => 250, 'null' => false]);
        }

        if (!$table->hasColumn('password_hash')) {
            $table->addColumn('password_hash', 'string', ['limit' => 250, 'null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('phone')) {
            $table->addColumn('phone', 'string', ['limit' => 100, 'null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('mobile')) {
            $table->addColumn('mobile', 'string', ['limit' => 100, 'null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('title')) {
            $table->addColumn('title', 'string', ['limit' => 10, 'null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('gender')) {
            $table->addColumn('gender', 'string', ['limit' => 10, 'null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('first_name')) {
            $table->addColumn('first_name', 'string', ['limit' => 100, 'null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('last_name')) {
            $table->addColumn('last_name', 'string', ['limit' => 100, 'null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('address')) {
            $table->addColumn('address', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR, 'null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('postcode')) {
            $table->addColumn('postcode', 'string', ['limit' => 10, 'null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('date_of_birth')) {
            $table->addColumn('date_of_birth', 'datetime', ['null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('company')) {
            $table->addColumn('company', 'string', ['limit' => 250, 'null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('marketing_optin')) {
            $table->addColumn('marketing_optin', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => false, 'default' => 0]);
        }

        if (!$table->hasColumn('is_blocked')) {
            $table->addColumn('is_blocked', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => false, 'default' => 0]);
        }

        $table->save();

        // Update all the columns to ensure they match spec:
        $table->changeColumn('email', 'string', ['limit' => 250, 'null' => false]);
        $table->changeColumn('password_hash', 'string', ['limit' => 250, 'null' => true, 'default' => null]);
        $table->changeColumn('phone', 'string', ['limit' => 100, 'null' => true, 'default' => null]);
        $table->changeColumn('mobile', 'string', ['limit' => 100, 'null' => true, 'default' => null]);
        $table->changeColumn('title', 'string', ['limit' => 10, 'null' => true, 'default' => null]);
        $table->changeColumn('gender', 'string', ['limit' => 10, 'null' => true, 'default' => null]);
        $table->changeColumn('first_name', 'string', ['limit' => 100, 'null' => true, 'default' => null]);
        $table->changeColumn('last_name', 'string', ['limit' => 100, 'null' => true, 'default' => null]);
        $table->changeColumn('address', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR, 'null' => true, 'default' => null]);
        $table->changeColumn('postcode', 'string', ['limit' => 10, 'null' => true, 'default' => null]);
        $table->changeColumn('date_of_birth', 'datetime', ['null' => true, 'default' => null]);
        $table->changeColumn('company', 'string', ['limit' => 250, 'null' => true, 'default' => null]);
        $table->changeColumn('marketing_optin', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => false, 'default' => 0]);
        $table->changeColumn('is_blocked', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => false, 'default' => 0]);

        if (!$table->hasIndex('email')) {
            $table->addIndex('email', ['unique' => true]);
        }

        $table->save();
    }

    protected function createContentItem()
    {
        $table = $this->table('content_item', ['id' => false, 'primary_key' => ['id']]);

        if (!$this->hasTable('content_item')) {
            $table->addColumn('id', 'char', ['limit' => 32, 'null' => false]);
            $table->create();
        }

        if (!$table->hasColumn('content')) {
            $table->addColumn('content', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => false, 'default' => null]);
        }

        $table->save();

        $table->changeColumn('content', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => false, 'default' => null]);

        $table->save();
    }

    protected function createLog()
    {
        $table = $this->table('log', ['id' => false, 'primary_key' => ['id']]);

        if (!$this->hasTable('log')) {
            $table->addColumn('id', 'integer', ['signed' => false]);
            $table->create();
        }

        if (!$table->hasColumn('type')) {
            $table->addColumn('type', 'integer', ['null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('scope')) {
            $table->addColumn('scope', 'string', ['limit' => 32, 'null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('scope_id')) {
            $table->addColumn('scope_id', 'string', ['limit' => 32, 'null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('user_id')) {
            $table->addColumn('user_id', 'integer', ['signed' => false, 'null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('message')) {
            $table->addColumn('message', 'string', ['limit' => 500, 'null' => false]);
        }

        if (!$table->hasColumn('log_date')) {
            $table->addColumn('log_date', 'datetime');
        }

        if (!$table->hasColumn('link')) {
            $table->addColumn('link', 'string', ['limit' => 500, 'null' => true, 'default' => null]);
        }

        $table->save();

        $table->changeColumn('type', 'integer', ['null' => true, 'default' => null]);
        $table->changeColumn('scope', 'string', ['limit' => 32, 'null' => true, 'default' => null]);
        $table->changeColumn('scope_id', 'string', ['limit' => 32, 'null' => true, 'default' => null]);
        $table->changeColumn('user_id', 'integer', ['signed' => false, 'null' => true, 'default' => null]);
        $table->changeColumn('message', 'string', ['limit' => 500, 'null' => false]);
        $table->changeColumn('log_date', 'datetime');
        $table->changeColumn('link', 'string', ['limit' => 500, 'null' => true, 'default' => null]);

        if (!$table->hasIndex('scope')) {
            $table->addIndex('scope', ['unique' => false]);
        }

        if (!$table->hasIndex('type')) {
            $table->addIndex('type', ['unique' => false]);
        }

        if (!$table->hasIndex('user_id')) {
            $table->addIndex('user_id', ['unique' => false]);
        }

        $table->save();
    }

    protected function createPermission()
    {
        $table = $this->table('permission', ['id' => false, 'primary_key' => ['id']]);

        if (!$this->hasTable('permission')) {
            $table->addColumn('id', 'integer', ['signed' => false]);
            $table->create();
        }

        if (!$table->hasColumn('user_id')) {
            $table->addColumn('user_id', 'integer', ['signed' => false, 'null' => false]);
        }

        if (!$table->hasColumn('uri')) {
            $table->addColumn('uri', 'string', ['limit' => 500, 'null' => false]);
        }

        if (!$table->hasColumn('can_access')) {
            $table->addColumn('can_access', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => false, 'default' => 0]);
        }

        if (!$table->hasIndex(['user_id', 'uri', 'can_access'])) {
            $table->addIndex(['user_id', 'uri', 'can_access'], ['unique' => false]);
        }

        $table->save();

        $table->changeColumn('user_id', 'integer', ['signed' => false, 'null' => false]);
        $table->changeColumn('uri', 'string', ['limit' => 500, 'null' => false]);
        $table->changeColumn('can_access', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => false, 'default' => 0]);

        $table->save();
    }

    protected function createSearchIndex()
    {
        $table = $this->table('search_index', ['id' => false, 'primary_key' => ['id']]);

        if (!$this->hasTable('search_index')) {
            $table->addColumn('id', 'integer', ['signed' => false]);
            $table->create();
        }

        if (!$table->hasColumn('word')) {
            $table->addColumn('word', 'string', ['limit' => 50, 'null' => false]);
        }

        if (!$table->hasColumn('model')) {
            $table->addColumn('model', 'string', ['limit' => 50, 'null' => false]);
        }

        if (!$table->hasColumn('content_id')) {
            $table->addColumn('content_id', 'string', ['limit' => 32, 'null' => false]);
        }

        if (!$table->hasColumn('instances')) {
            $table->addColumn('instances', 'integer', ['null' => false, 'default' => 1]);
        }

        $table->save();

        $table->changeColumn('word', 'string', ['limit' => 50, 'null' => false]);
        $table->changeColumn('model', 'string', ['limit' => 50, 'null' => false]);
        $table->changeColumn('content_id', 'string', ['limit' => 32, 'null' => false]);
        $table->changeColumn('instances', 'integer', ['null' => false, 'default' => 1]);

        if (!$table->hasIndex(['word', 'instances', 'model', 'content_id'])) {
            $table->addIndex(['word', 'instances', 'model', 'content_id'], ['unique' => false]);
        }

        $table->save();
    }

    protected function createSetting()
    {
        $table = $this->table('setting', ['id' => false, 'primary_key' => ['id']]);

        if (!$this->hasTable('setting')) {
            $table->addColumn('id', 'integer', ['signed' => false]);
            $table->create();
        }

        if (!$table->hasColumn('key')) {
            $table->addColumn('key', 'string', ['limit' => 100, 'null' => false]);
        }

        if (!$table->hasColumn('value')) {
            $table->addColumn('value', 'text', ['null' => true]);
        }

        if (!$table->hasColumn('scope')) {
            $table->addColumn('scope', 'string', ['limit' => 100, 'null' => false]);
        }

        if (!$table->hasColumn('hidden')) {
            $table->addColumn('hidden', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => true, 'default' => 0]);
        }

        $table->save();

        $table->changeColumn('key', 'string', ['limit' => 100, 'null' => false]);
        $table->changeColumn('value', 'text', ['null' => true]);
        $table->changeColumn('scope', 'string', ['limit' => 100, 'null' => false]);
        $table->changeColumn('hidden', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => true, 'default' => 0]);

        if (!$table->hasIndex(['key', 'scope'])) {
            $table->addIndex(['key', 'scope'], ['unique' => true]);
        }

        if (!$table->hasIndex(['scope'])) {
            $table->addIndex(['scope'], ['unique' => false]);
        }

        $table->save();
    }

    protected function createUser()
    {
        $table = $this->table('user', ['id' => false, 'primary_key' => ['id']]);

        if (!$this->hasTable('user')) {
            $table->addColumn('id', 'integer', ['signed' => false]);
            $table->create();
        }

        if (!$table->hasColumn('email')) {
            $table->addColumn('email', 'string', ['limit' => 250, 'null' => false]);
        }

        if (!$table->hasColumn('hash')) {
            $table->addColumn('hash', 'string', ['limit' => 250, 'null' => false]);
        }

        if (!$table->hasColumn('name')) {
            $table->addColumn('name', 'string', ['limit' => 250, 'null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('is_admin')) {
            $table->addColumn('is_admin', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => false, 'default' => 0]);
        }

        if (!$table->hasColumn('is_hidden')) {
            $table->addColumn('is_hidden', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => false, 'default' => 0]);
        }

        if (!$table->hasColumn('date_added')) {
            $table->addColumn('date_added', 'datetime', ['null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('date_active')) {
            $table->addColumn('date_active', 'datetime', ['null' => true, 'default' => null]);
        }

        if (!$table->hasColumn('active')) {
            $table->addColumn('active', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => false, 'default' => 1]);
        }

        $table->save();

        $table->changeColumn('email', 'string', ['limit' => 250, 'null' => false]);
        $table->changeColumn('hash', 'string', ['limit' => 250, 'null' => false]);
        $table->changeColumn('name', 'string', ['limit' => 250, 'null' => true, 'default' => null]);
        $table->changeColumn('is_admin', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => false, 'default' => 0]);
        $table->changeColumn('is_hidden', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => false, 'default' => 0]);
        $table->changeColumn('date_added', 'datetime', ['null' => true, 'default' => null]);
        $table->changeColumn('date_active', 'datetime', ['null' => true, 'default' => null]);
        $table->changeColumn('active', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'null' => false, 'default' => 1]);

        if (!$table->hasIndex(['email'])) {
            $table->addIndex(['email'], ['unique' => true]);
        }

        $table->save();
    }
}
