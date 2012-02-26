<?php
namespace Framework\Packages\UserAuth\Drivers;
use \Framework\Packages\UserAuth\AbstractUserAuthDriver;

class NativePhpSession extends AbstractUserAuthDriver
{
    protected $_started = false;

    /**
     * Start php session
     */
    public function __construct()
    {
        session_start();
        $this->_started = true;
    }

    /**
     * @param $key
     * @return bool
     */
    public function getData($key)
    {
        if (!$this->_started) session_start();
        return isset($_SESSION["nps_$key"]) ? $_SESSION["nps_$key"] : false;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setData($key, $value)
    {
        if (!$this->_started) session_start();
        $_SESSION["nps_$key"] = $value;
    }

    public function out()
    {
        parent::out();
        session_destroy();
        $this->_started = false;
    }
}
