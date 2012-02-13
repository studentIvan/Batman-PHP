<?php
namespace Framework\Packages\UserAuth;
use \Framework\Common\WebRequest;
use \Framework\Common\WebResponse;
use \Framework\Packages\UserAuth\Interfaces\UserAuthDriverInterface;

abstract class AbstractUserAuthDriver implements UserAuthDriverInterface
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
     * @param string $login
     * @param string $password
     */
    public function auth($login, $password) {
        $this->setData('auth', true);
        $this->setData('login', $login);
        $this->setData('password', $password);
    }
}
