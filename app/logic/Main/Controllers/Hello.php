<?php
namespace Main\Controllers;

class Hello extends \Framework\Core\Controller
{
    public function index($name)
    {
        $this->tpl->match('name', $name);
        return $this->tpl->render('hello');
    }
}