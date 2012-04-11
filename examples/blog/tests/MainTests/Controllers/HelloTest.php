<?php
namespace MainTests\Controllers;

use \Framework\Core\Template,
    \Main\Controllers\Hello;

require_once 'PHPUnit/Autoload.php';

class HelloTest extends \PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        $this->component = new Hello();
        $this->component->tpl = new Template('Main');
    }
    
    public function test()
    {
        $this->assertRegExp('/Hello <b>World<\/b>/', $this->component->index('World'));
    }
}