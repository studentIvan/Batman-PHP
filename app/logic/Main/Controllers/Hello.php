<?php
namespace Main\Controllers;
use \Framework\Common\WebRequest;
use \Framework\Common\WebResponse;
use \Framework\Core\Controller;

class Hello extends Controller
{
    public function index($name, WebRequest $request, WebResponse $response) {
        $this->tpl->match('name', $name);
        $response->setContent($this->tpl->render('hello'));
        $response->send();
    }
}