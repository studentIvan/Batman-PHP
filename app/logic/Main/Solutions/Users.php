<?php
namespace Main\Solutions;

use \Framework\Common\DBSolution,
    \Main\Models\User;

class Users extends DBSolution
{
    /**
     * @param $username
     * @param $password
     * @administrative
     */
    public function add($username, $password)
    {
        $this->save(new User($username, $password));
    }

    /**
     * @return array
     */
    public function listing()
    {
        $sql = $this->procedure()->select('*')->from($this, 'u');
        return $this->db->fetchAll($sql);
    }
}