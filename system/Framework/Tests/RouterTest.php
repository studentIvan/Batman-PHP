<?php
namespace Framework\Tests;
require_once 'PHPUnit/Autoload.php';
use \Framework\Core\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{
	public function test()
	{
		$_SERVER['QUERY_STRING'] = '';
		$this->assertEquals(array(
			'bundleName' => 'Main',
			'controllerName' => 'Main',
			'methodName' => 'index',
			'option' => null,
		), Router::directing());
		
		$_SERVER['QUERY_STRING'] = 'Main::Main::index::4';
		$this->assertEquals(array(
			'bundleName' => 'Main',
			'controllerName' => 'Main',
			'methodName' => 'index',
			'option' => 4,
		), Router::directing());
		
		$_SERVER['QUERY_STRING'] = 'Main::Fucking';
		try {
            $this->assertEquals(array(
                'bundleName' => 'Main',
                'controllerName' => 'Main',
                'methodName' => 'index',
                'option' => null,
            ), Router::directing());
        } catch (\Exceptions\NotFoundException $e) {
            echo "Main::Fucking Not Found Exception\n";
        }
	}
}