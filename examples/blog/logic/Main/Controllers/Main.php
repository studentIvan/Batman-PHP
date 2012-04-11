<?php
namespace Main\Controllers;

class Main extends \Framework\Core\Controller
{
    public function index()
    {
        $posts = new \Main\Solutions\Posts();
        $this->tpl->match('posts', $posts->read());
        return $this->tpl->render('posts');
    }
}