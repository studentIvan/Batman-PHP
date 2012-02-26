<?php
namespace Framework\Packages\UserAuth\Interfaces;
use \Framework\Common\WebRequest;
use \Framework\Common\WebResponse;

interface UserAuthDriverInterface
{
    public function setRequest(WebRequest $request);
    public function setResponse(WebResponse $response);
    public function setData($key, $value);
    public function getData($key);
    public function auth($login, $password);
    public function out();
}
