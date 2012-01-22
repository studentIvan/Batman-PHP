<?php
namespace MainTests\Controllers;
require_once 'PHPUnit/Autoload.php';

/**
 * Unit test of controller Hello
 *
 * @todo create method in this test, e.g. mytest
 *
 * Using: php bin/manager.php test Hello mytest
 * for specific bundle use: php bin/manager.php test Hello mytest c Main
 *
 * See PHPUnit manual for more:
 * @link http://www.phpunit.de/manual/3.6/en/writing-tests-for-phpunit.html
 */
class HelloTest extends \PHPUnit_Framework_TestCase {

    public function __construct() {
        $this->component = new \Main\Controllers\Hello();
        $this->component->tpl = new \Framework\Core\Template('Main');
    }
    
    public function test() {
        $request = new \Symfony\Component\HttpFoundation\Request();
        $response = new \Symfony\Component\HttpFoundation\Response();
        ob_start();
        $this->component->index('World', $request, $response);
        $content = ob_get_clean();
        $this->assertRegExp('/Hello World/', $content);
    }

}