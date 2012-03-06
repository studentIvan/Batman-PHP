<?php
namespace Main\Controllers;

use \Framework\Common\WebRequest,
    \Framework\Common\WebResponse,
    \Main\Solutions;

class Main
{
    function index(WebResponse $response)
    {
        $welcome = new Solutions\Welcome();
        $response->send($welcome->to());
    }
}