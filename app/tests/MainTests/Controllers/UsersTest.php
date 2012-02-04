<?php
namespace MainTests\Controllers;
use \Framework\Common\WebRequest;
use \Framework\Common\WebResponse;
use \Framework\Core\Template;
use \Main\Controllers\Users;
use \PHPUnit_Framework_TestCase as TestCase;

require_once 'PHPUnit/Autoload.php';

/**
 * Unit test of controller Users
 *
 * Using: php bin/manager.php controller:test Users mytest (name test by default)
 * for specific bundle use: php bin/manager.php controller:test Users test Main
 *
 * See PHPUnit manual for more:
 * @link http://www.phpunit.de/manual/3.6/en/writing-tests-for-phpunit.html
 */
class UsersTest extends TestCase {

    public function __construct() {
        $this->component = new Users();
        $this->component->tpl = new Template('Main');
    }

    public function test() {
        $request = new WebRequest();
        $response = new WebResponse();
        ob_start();
        $this->component->index($response, $request);
        $content = ob_get_clean();
        //$this->assertEquals('', $content);
        //$this->assertRegExp('/foo/', $content);
    }

}