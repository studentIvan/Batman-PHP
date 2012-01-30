<?php
namespace Framework\Common;
use \Doctrine\DBAL\Schema\Schema;
use \Doctrine\DBAL\Schema\Table;
use \Framework\Core\Config;

class UnicodeSchema extends Schema
{
    /**
     * Create schema table with specific encoding
     *
     * @param string $tableName
     * @return \Doctrine\DBAL\Schema\Table
     */
    public function createTable($tableName) {
        $charset = Config::get('database_migration', 'charset');
        $collate = Config::get('database_migration', 'collate');
        $table = new Table($tableName, array(), array(), array(), 0, array(
            'charset' => ($charset) ? $charset : null,
            'collate' => ($collate) ? $collate : null,
        ));
        $this->_addTable($table);
        return $table;
    }
}
