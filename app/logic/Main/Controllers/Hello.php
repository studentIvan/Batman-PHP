<?php
namespace Main\Controllers;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

/**
 * Hello controller
 *
 * @property \Framework\Core\Template $tpl
 */
class Hello {

    public function index($name, Request $request, Response $response) {
        $this->tpl->match('name', $name);
        $response->setContent($this->tpl->render('hello'));
        $response->send();
    }
}