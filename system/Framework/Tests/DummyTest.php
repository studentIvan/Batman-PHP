<?php
namespace Framework\Tests;
require_once 'PHPUnit/Autoload.php';
//use \Framework\Common\Class;
 
class DummyTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $this->assertEquals(123, 123);
    }
}