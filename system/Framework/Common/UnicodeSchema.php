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
        $migrateConfig = Config::get('database_migration');
        if (!is_array($migrateConfig)) $migrateConfig = array();
        $table = new Table($tableName, array(), array(), array(), 0, $migrateConfig);
        $this->_addTable($table);
        return $table;
    }
}
