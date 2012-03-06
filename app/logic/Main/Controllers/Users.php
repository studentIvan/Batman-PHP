<?php
namespace Main\Controllers;

use \Framework\Common\WebRequest,
    \Framework\Common\WebResponse;

class Users extends \Framework\Core\Controller
{
    public function __construct()
    {
        $this->users = new \Main\Solutions\Users();
    }

    public function index(WebResponse $response, WebRequest $request)
    {
        $this->tpl->match('users', $this->users->listing());
        $response->send($this->tpl->render('users/list'));
    }

    public function add(WebResponse $response, WebRequest $request)
    {
        if ($username = $request->postStr('username'))
        {
            $this->users->add($username, rand(100, 999));
            $this->tpl->match('username', $username);
            $response->send($this->tpl->render('users/add'));
        }
        else
        {
            header('Location: /users/');
        }
    }
}