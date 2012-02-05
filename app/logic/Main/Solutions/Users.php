<?php
namespace Main\Solutions;
use \Framework\Common\DBSolution;
use \Main\Models\User;

class Users extends DBSolution
{
    public function add($username, $password) {
        $this->save(new User($username, $password));
    }

    public function listing() {
        $sql = $this->procedure()->select('*')->from($this, 'u');
        return $this->db->fetchAll($sql);
    }
}