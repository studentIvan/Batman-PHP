<?php
namespace MainTests\Controllers;
use \Framework\Common\WebRequest;
use \Framework\Common\WebResponse;
use \Framework\Core\Template;
use \Main\Controllers\Hello;

require_once 'PHPUnit/Autoload.php';

/**
 * Unit test of controller Hello
 *
 * See PHPUnit manual for more:
 * @link http://www.phpunit.de/manual/3.6/en/writing-tests-for-phpunit.html
 */
class HelloTest extends \PHPUnit_Framework_TestCase {

    public function __construct() {
        $this->component = new Hello();
        $this->component->tpl = new Template('Main');
    }
    
    public function test() {
        $request = new WebRequest();
        $response = new WebResponse();
        ob_start();
        $this->component->index('World', $response, $request);
        $content = ob_get_clean();
        $this->assertRegExp('/Hello <b>World<\/b>/', $content);
    }

}