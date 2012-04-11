<?php
namespace Framework\Packages\UserAuth\Interfaces;

use \Framework\Common\WebRequest,
    \Framework\Common\WebResponse;

interface UserAuthDriverInterface
{
    public function setRequest(WebRequest $request);
    public function setResponse(WebResponse $response);
    public function setData($key, $value);
    public function getData($key);
    public function getSpecialData($key);
    public function setSpecialData($key, $value);
    public function auth($login, $password);
    public function out();
}
