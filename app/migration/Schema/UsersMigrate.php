<?php
namespace Schema;

/**
 * @link http://www.doctrine-project.org/docs/dbal/2.0/en/reference/schema-representation.html
 */
class UsersMigrate extends \Framework\Common\Migrate
{
    public function __invoke()
    {
        $table = $this->schema->createTable('users');
        $table->addColumn('id', $this->integer, array('unsigned' => true, 'autoincrement' => true));
        $table->addColumn('username', $this->string, array('length' => '32'));
        $table->addColumn('password', $this->string, array('length' => '32'));
        $table->addColumn('created', $this->datetime);
        $table->setPrimaryKey(array('id'));
    }
}
