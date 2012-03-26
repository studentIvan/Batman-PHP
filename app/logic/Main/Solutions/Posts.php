<?php
namespace Main\Solutions;

class Posts extends \Framework\Common\DBSolution
{
    /**
     * @admin_method New post
     * @admin_textarea message
     *
     * @param $title
     * @param $message
     * @return bool
     */
    public function add($title, $message)
    {
        if (!$this->save(new \Main\Models\Post($title, $message)))
            throw new \Exception('failed');
    }

    /**
     * @admin_method Display posts
     *
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