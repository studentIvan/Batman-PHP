<?php
namespace MainTests\Controllers;
use \Framework\Common\WebRequest;
use \Framework\Common\WebResponse;
use \Framework\Core\Template;
use \Main\Controllers\Main;
use \PHPUnit_Framework_TestCase as TestCase;

require_once 'PHPUnit/Autoload.php';

class MainTest extends TestCase {

	public function __construct() {
		$this->component = new Main();
	}
	
	public function test() {
        $request = WebRequest::createFromGlobals();
        $response = new WebResponse();
        ob_start();
        $this->component->index($response, $request);
        $content = ob_get_clean();
        $this->assertEquals('eeeee', $content);
	}
}