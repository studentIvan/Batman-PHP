<?php
namespace Main\Solutions;

use \Framework\Common\DBSolution,
    \Main\Models\User;

class Users extends DBSolution
{
    /**
     * @admin_method Add new user
     *
     * @param $username
     * @param $password
     */
    public function add($username, $password)
    {
        $this->save(new User($username, $password));
    }

    /**
     * @admin_method Display users
     *
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function listing($offset = 0, $limit = 30)
    {
        $sql = $this->procedure()
                ->select('*')
                ->from($this)
                ->setFirstResult($offset)
                ->setMaxResults($limit);

        return $this->db->fetchAll($sql);
    }
}