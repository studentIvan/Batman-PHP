<?php
namespace Framework\Tests;
require_once 'PHPUnit/Autoload.php';
use \Framework\Common\Registry;
 
class RegistryTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        Registry::set('hello', 'world');
        $this->assertEquals('world', Registry::get('hello'));

        Registry::set('demo', 123);
        $this->assertEquals(123, Registry::get('demo'));

        Registry::set('myarray', array('a','b','c'));
        $this->assertEquals(array('a','b','c'), Registry::get('myarray'));
    }
}
