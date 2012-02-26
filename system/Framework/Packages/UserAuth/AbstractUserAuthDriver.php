<?php
namespace Framework\Packages\UserAuth;
use \Framework\Common\WebRequest;
use \Framework\Common\WebResponse;

abstract class AbstractUserAuthDriver implements Interfaces\UserAuthDriverInterface
{
    /**
     * @var \Framework\Common\WebRequest
     */
    protected $request;

    /**
     * @var \Framework\Common\WebResponse
     */
    protected $response;

    /**
     * @var array
     */
    protected $_data = array();

    /**
     * Set Request
     *
     * @param \Framework\Common\WebRequest $request
     */
    public function setRequest(WebRequest $request) {
        $this->request = $request;
    }

    /**
     * @param \Framework\Common\WebResponse $response
     */
    public function setResponse(WebResponse $response) {
        $this->response = $response;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setData($key, $value) {
        $this->_data[$key] = $value;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getData($key) {
        return isset($this->_data[$key]) ? $this->_data[$key] : false;
    }

    /**
     * @param $login
     * @param $password
     * @throws Exceptions\AuthException
     */
    public function auth($login, $password)
    {
        if ($this->getData('auth'))
        {
            throw new Exceptions\AuthException('User is already logged in', 1);
        }
        else
        {
            $this->setData('auth', true);
            $this->setData('login', $login);
        }
    }

    /**
     * Exit
     */
    public function out()
    {
        if (!$this->getData('auth'))
        {
            throw new Exceptions\AuthException('User is not logged in', 2);
        }
        else
        {
            $this->setData('auth', false);
            $this->setData('login', false);
        }
    }
}
