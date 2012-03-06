<?php
namespace Main\Controllers;

use \Framework\Common\WebRequest,
    \Framework\Common\WebResponse;

class Hello extends \Framework\Core\Controller
{
    public function index($name, WebResponse $response)
    {
        $this->tpl->match('name', $name);
        $response->send($this->tpl->render('hello'));
    }
}