<?php
namespace Main\Solutions;

use \Framework\Common\DBSolution,
    \Main\Models\User;

class Users extends DBSolution
{
    /**
     * @param $username
     * @param $password
     *
     * @admin_method Add new user
     */
    public function add($username, $password)
    {
        $this->save(new User($username, $password));
    }

    /**
     * @admin_method Display users
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function listing($offset = 0, $limit = 30)
    {
        $sql = $this->procedure()
                ->select('*')
                ->from($this, 'u')
                ->setFirstResult($offset)
                ->setMaxResults($limit);

        return $this->db->fetchAll($sql);
    }
}