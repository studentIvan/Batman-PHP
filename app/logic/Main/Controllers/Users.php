<?php
namespace Main\Controllers;
use \Framework\Common\WebRequest;
use \Framework\Common\WebResponse;
use \Framework\Core\Controller;
use \Framework\Common\Database;

/**
 * Users controller
 * 
 * @link http://api.symfony.com/2.0/Symfony/Component/HttpFoundation.html
 */
class Users extends Controller
{
    public function __construct() {
        $this->conn = Database::newInstance();
        $this->users = new \Main\Solutions\Users($this->conn);
    }

    public function index(WebResponse $response, WebRequest $request) {
        $this->tpl->match('users', $this->users->listing());
        $response->send($this->tpl->render('users/list'));
    }

    public function add(WebResponse $response, WebRequest $request) {
        $username = $request->postStr('username');
        if ($username) {
            $this->users->add($username, rand(100, 999));
            $this->tpl->match('username', $username);
            $response->send($this->tpl->render('users/add'));
        } else {
            header('Location: /users/');
            exit;
        }
    }
}