<?php
namespace MainTests\Controllers;
use \Framework\Common\WebRequest;
use \Framework\Common\WebResponse;
use \Framework\Core\Template;
use \Main\Controllers\Bus;

require_once 'PHPUnit/Autoload.php';

/**
 * Unit test of controller Bus
 *
 * @todo create method in this test, e.g. mytest
 *
 * Using: php bin/manager.php test Bus mytest
 * for specific bundle use: php bin/manager.php test Bus mytest c Main
 *
 * See PHPUnit manual for more:
 * @link http://www.phpunit.de/manual/3.6/en/writing-tests-for-phpunit.html
 */
class BusTest extends \PHPUnit_Framework_TestCase {

    public function __construct() {
        $this->component = new Bus();
        $this->component->tpl = new Template('Main');
    }

    public function index() {
        $request = new WebRequest();
        $response = new WebResponse();
        ob_start();
        $this->component->index($request, $response);
        $content = ob_get_clean();
        //$this->assertEquals('', $content);
        //$this->assertRegExp('/foo/', $content);
    }

}