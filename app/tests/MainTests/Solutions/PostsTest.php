<?php
namespace MainTests\Solutions;

use \Main\Solutions\Posts,
    \PHPUnit_Framework_TestCase as TestCase;

require_once 'PHPUnit/Autoload.php';

class PostsTest extends TestCase
{

    public function __construct()
    {
        $this->component = new Posts();
    }

    public function test()
    {

    }
    
}