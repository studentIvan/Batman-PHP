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
 * Using: php bin/manager.php test Hello mytest
 * for specific bundle use: php bin/manager.php test Hello mytest c Main
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
        $this->component->index('World', $request, $response);
        $content = ob_get_clean();
        $this->assertRegExp('/Hello <b>World<\/b>/', $content);
    }

}