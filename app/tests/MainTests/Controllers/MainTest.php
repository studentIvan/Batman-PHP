<?php
namespace MainTests\Controllers;

use \Framework\Common\WebRequest,
    \Framework\Common\WebResponse,
    \Framework\Core\Template,
    \Main\Controllers\Main,
    \PHPUnit_Framework_TestCase as TestCase;

require_once 'PHPUnit/Autoload.php';

class MainTest extends TestCase
{
    public function __construct()
    {
        $this->component = new Main();
        $this->component->tpl = new Template('Main');
    }

    public function test()
    {
        //$request = new WebRequest();
        //$response = new WebResponse();

        //ob_start();
        //$this->component->index($request, $response);
        //$content = ob_get_clean();

        //$this->assertEquals('', $content);
        //$this->assertRegExp('/foo/', $content);
    }
}