<?php
namespace Framework\Common;
use \Doctrine\DBAL\Connection;

abstract class DBSolution
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @param bool|\Doctrine\DBAL\Connection $conn
     */
    public function __construct($conn = false)
    {
        if (($conn instanceof Connection) == false)
        {
            $conn = Database::getInstance();
        }

        $this->db = $conn;
        $class = get_called_class();
        $this->tableName = strtolower(substr($class, strrpos($class, '\\')+1));
    }

    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    protected function procedure()
    {
        return $this->db->createQueryBuilder();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->tableName;
    }

    /**
     * @param Model $mixed
     * @param null|string $table
     * @return bool
     */
    protected function save(Model $mixed, $table = null)
    {
        if ($table == null) $table = $this->tableName;
        return ($this->db->insert($table, $mixed->toArray()) > 0);
    }
}
