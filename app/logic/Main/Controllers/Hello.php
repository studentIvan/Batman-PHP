<?php
namespace Main\Controllers;

/**
 * Hello controller
 */
class Hello {

    public function index($name = 'Anonymous') {
        echo "Hello $name";
    }
}