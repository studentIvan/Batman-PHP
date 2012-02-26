<?php
namespace Framework\Packages;
use \Framework\Common\WebRequest;
use \Framework\Common\WebResponse;

class UserAuth
{
    /**
     * @var \Framework\Packages\UserAuth\AbstractUserAuthDriver
     */
    protected $driver;

    /**
     * @param string $driver
     */
    public function __construct($driver = 'NativePhpSession') {
        $callStr = '\\Framework\\Packages\\UserAuth\\Drivers\\' . $driver;
        $this->driver = new $callStr();
    }

    /**
     * @param \Framework\Common\WebRequest $request
     * @param \Framework\Common\WebResponse $response
     */
    public function init(WebRequest $request, WebResponse $response) {
        $this->driver->setRequest($request);
        $this->driver->setResponse($response);
    }

    /**
     * @return bool
     */
    public function isAuth() {
        return (bool)$this->driver->getData('auth');
    }

    /**
     * @param string $login
     * @param string $password
     * @return array
     */
    public function auth($login, $password = '') {
        return $this->driver->auth($login, $password);
    }

    /**
     * @return string
     */
    public function getLogin() {
        return $this->driver->getData('login');
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->driver->getData('name');
    }

    /**
     * @return int
     */
    public function getUserId() {
        return intval($this->driver->getData('uid'));
    }
}
