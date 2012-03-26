<?php
namespace Schema;

/**
 * @link http://www.doctrine-project.org/docs/dbal/2.0/en/reference/schema-representation.html
 */
class PostsMigrate extends \Framework\Common\Migrate
{
    public function __invoke()
    {
        $table = $this->schema->createTable('posts');
        $table->addColumn('id', $this->integer, array('unsigned' => true, 'autoincrement' => true));
        $table->addColumn('title', $this->string, array('length' => '50'));
        $table->addColumn('message', $this->string, array('length' => '150'));
        $table->addColumn('created_at', $this->datetime);
        $table->setPrimaryKey(array('id'));
    }
}
