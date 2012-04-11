<?php
namespace Framework\Packages;

use \Framework\Common\WebRequest,
    \Framework\Common\WebResponse,
    \Exceptions\ForbiddenException;

class UserAuth
{
    /**
     * @var \Framework\Packages\UserAuth\AbstractUserAuthDriver
     */
    protected $driver;

    /**
     * @param string $driver
     */
    public function __construct($driver = 'NativePhpSession')
    {
        $callStr = '\\Framework\\Packages\\UserAuth\\Drivers\\' . $driver;
        $this->driver = new $callStr();
    }

    /**
     * @param \Framework\Common\WebRequest $request
     * @param \Framework\Common\WebResponse $response
     */
    public function init(WebRequest $request, WebResponse $response)
    {
        $this->driver->setRequest($request);
        $this->driver->setResponse($response);
    }

    /**
     * @return bool
     */
    public function isAuth()
    {
        return (bool)$this->driver->getData('auth');
    }

    /**
     * If user not authorized throw new ForbiddenException
     *
     * @throws \Exceptions\ForbiddenException
     */
    public function ifNotAuthorizedThrowForbidden()
    {
        if (!$this->isAuth()) throw new ForbiddenException();
    }

    /**
     * @param string $login
     * @param string $password
     */
    public function auth($login, $password = '')
    {
        $this->driver->auth($login, $password);
    }

    /**
     * Exit
     */
    public function out()
    {
        $this->driver->out();
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->driver->getData('login');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->driver->getData('name');
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->driver->getData('hash');
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return intval($this->driver->getData('uid'));
    }

    /**
     * @param array $configuration
     */
    public function setConfiguration(array $configuration)
    {
        $this->driver->setData('config', $configuration);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setSpecialData($key, $value)
    {
        $this->driver->setSpecialData($key, $value);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getSpecialData($key)
    {
        return $this->driver->getSpecialData($key);
    }
}
