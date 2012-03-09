<?php
namespace Main\Controllers;

class Main
{
    public function index()
    {
        $welcome = new \Main\Solutions\Welcome();
        return $welcome->to();
    }
}