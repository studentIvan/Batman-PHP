<?php
namespace MainTests\Controllers;

use \Framework\Common\WebRequest,
    \Framework\Common\WebResponse,
    \Framework\Core\Template,
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
        $request = new WebRequest();
        $response = new WebResponse();

        ob_start();
        $this->component->index('World', $response, $request);
        $content = ob_get_clean();

        $this->assertRegExp('/Hello <b>World<\/b>/', $content);
    }

}