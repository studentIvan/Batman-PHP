<?php
namespace Framework\Common;
use \Doctrine\DBAL\Connection;

abstract class DBSolution
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;

    public function __construct(Connection $conn) {
        $this->db = $conn;
    }
}
