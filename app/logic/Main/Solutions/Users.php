<?php
namespace Main\Solutions;
use \Doctrine\DBAL\Connection;

/**
 * User solution
 *
 */
class Users
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;

    public function __construct(Connection $conn) {
        $this->db = $conn;
    }

    public function add($username, $password) {
        $this->db->insert('users', array(
            'username' => $username,
            'password' => md5($password),
            'created' => date('Y-m-d H:i:sP'),
        ));
    }

    public function listing() {
        return $this->db->fetchAll("SELECT * FROM users");
    }
}