<?php
namespace Main\Solutions;
use \Framework\Common\DBSolution;

class Users extends DBSolution
{
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