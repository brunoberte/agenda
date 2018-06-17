<?php


use Phinx\Migration\AbstractMigration;

class CreateContactsTable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $users = $this->table('contacts');
        $users->addColumn('first_name', 'string', ['limit' => 100])
            ->addColumn('last_name', 'string', ['limit' => 100])
            ->addColumn('email', 'string', ['limit' => 100])
            ->addColumn('main_number', 'string', ['limit' => 30])
            ->addColumn('secondary_number', 'string', ['limit' => 30])
            ->addColumn('created', 'datetime')
            ->addColumn('updated', 'datetime', ['null' => true])
            ->addIndex('email')
            ->addIndex('first_name')
            ->addIndex('last_name')
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('contacts');
    }
}
