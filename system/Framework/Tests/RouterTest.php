<?php
namespace Framework\Tests;
require_once 'PHPUnit/Autoload.php';
use \Framework\Core\Router;

class RouterTest extends \PHPUnit_Framework_TestCase {

	public function test()
	{
		$_SERVER['QUERY_STRING'] = '';
		$this->assertEquals(array(
			'bundle' => 'Main',
			'controller' => 'Main',
			'method' => 'index',
			'option' => null,
		), Router::directing());
		
		$_SERVER['QUERY_STRING'] = 'Blabla';
		$this->assertEquals(array(
			'bundle' => 'Blabla',
			'controller' => 'Main',
			'method' => 'index',
			'option' => null,
		), Router::directing());
		
		$_SERVER['QUERY_STRING'] = 'Main::Main::index::4';
		$this->assertEquals(array(
			'bundle' => 'Main',
			'controller' => 'Main',
			'method' => 'index',
			'option' => 4,
		), Router::directing());
		
		$_SERVER['QUERY_STRING'] = 'Main::Fucking';
		$this->assertEquals(array(
			'bundle' => 'Main',
			'controller' => 'Main',
			'method' => 'index',
			'option' => null,
		), Router::directing());
	}
}