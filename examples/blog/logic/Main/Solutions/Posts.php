<?php
namespace Main\Solutions;

class Posts extends \Framework\Common\DBSolution
{
    /**
     * @admin_method New blog post
     * @admin_textarea content
     * @param $title
     * @param $content
     * @param $tags
     */
    public function create($title, $content, $tags = '')
    {
        $this->save(new \Main\Models\Post($title, $content, $tags));
    }

    /**
     * @param $postId
     * @param $title
     * @param $content
     * @param $tags
     * @return void
     */
    public function update($postId, $title, $content, $tags = '')
    {
        if (!$this->db->update($this,
            array('title' => $title, 'content' => $content, 'tags' => $tags),
            array('id' => $postId)) > 0)
            throw new \BadFunctionCallException();
    }

    /**
     * @param $postId
     * @return void
     */
    public function delete($postId)
    {
        if (!$this->db->delete($this, array('id' => $postId)))
            throw new \BadFunctionCallException();
    }

    /**
     * @admin_method View posts
     * @return array
     */
    public function read()
    {
        return $this->db->fetchAll(
            $this->procedure()
            ->select('*')
            ->from($this)
            ->orderBy('created_at', 'desc')
        );
    }
}