<?php
namespace Main\Controllers;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use \Main\Solutions;

class Main {
	function __construct() {
		$this->simple = new Solutions\Simple();
	}
	
	function index() {
		$response = new Response($this->simple->test());
		$response->send();
	}
}