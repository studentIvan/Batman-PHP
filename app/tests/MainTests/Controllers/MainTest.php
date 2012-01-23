<?php
namespace MainTests\Controllers;
use \Framework\Common\WebRequest;
use \Framework\Common\WebResponse;
use \Framework\Core\Template;
use \Main\Controllers\Main;

require_once 'PHPUnit/Autoload.php';

class MainTest extends \PHPUnit_Framework_TestCase {

	public function __construct() {
		$this->component = new Main();
	}
	
	public function test() {
        /*$_POST['myarray'] = array(1, 2, 3);
        $_POST['myinteger'] = 100;
        $_POST['mystring'] = 'test';
        $_POST['mybool'] = false;
        $_POST['mynull'] = null;*/
        $request = WebRequest::createFromGlobals();
        $response = new WebResponse();
        //$defender = new \Framework\Common\Defender();
        //var_dump($defender->needArray($request->post('mynull')));
        /*var_dump($request->postArray('myarray'));
        var_dump($request->postInt('myinteger'));
        var_dump($request->postStr('mystring'));
        var_dump($request->postArray('myinteger'));
        var_dump($request->postInt('mystring'));
        var_dump($request->postStr('myarray'));*/
		ob_start();
        $this->component->index($request, $response);
        $content = ob_get_clean();
        $this->assertEquals('eeeee', $content);
	}
}