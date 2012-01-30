<?php
namespace Main\Controllers;
use \Framework\Common\WebRequest;
use \Framework\Common\WebResponse;
use \Main\Solutions;

class Main {
	function __construct() {
		$this->simple = new Solutions\Simple();
	}
	
	function index(WebResponse $response) {
		$response->send($this->simple->test());
	}
}