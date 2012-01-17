<?php
namespace MainTests\Controllers;
require_once 'PHPUnit/Autoload.php';

class MainTest extends \PHPUnit_Framework_TestCase {

	public function __construct() {
		$this->component = new \Main\Controllers\Main();
	}
	
	public function index() {
	
		$_GET['name'] = 'Student Ivan';
		
		ob_start();
        $this->component->index();
        $content = ob_get_clean();
        $this->assertEquals('Hello Student Ivan', $content);
	}
}