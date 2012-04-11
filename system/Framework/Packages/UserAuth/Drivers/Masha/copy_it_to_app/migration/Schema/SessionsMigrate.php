<?php
namespace Schema;

/**
 * @link http://www.doctrine-project.org/docs/dbal/2.0/en/reference/schema-representation.html
 */
class SessionsMigrate extends \Framework\Common\Migrate
{
    public function __invoke()
    {
        $table = $this->schema->createTable('sessions');
        $table->addColumn('identify', $this->string, array('length' => '32'));
        $table->addColumn('client', $this->integer);
        $table->addColumn('starttime', $this->datetime);
        $table->addColumn('agent', $this->string, array('length' => '150'));
        $table->addColumn('special', $this->string, array('length' => '150'));
        $table->addColumn('auth_user_id', $this->integer);
        $table->addIndex(array('auth_user_id'));
        $table->setPrimaryKey(array('identify'));
    }
}
