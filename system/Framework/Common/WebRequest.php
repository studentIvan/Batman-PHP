<?php
namespace Framework\Common;
use \Symfony\Component\HttpFoundation\Request;
use \Exceptions\ForbiddenException;

class WebRequest extends Request
{
    /**
     * @throws \Exception
     */
    public function ifNotAjaxRequestThrowForbidden()
    {
        if (!$this->isXmlHttpRequest()) {
            throw new ForbiddenException('Ajax only');
        }
    }

    /**
     * @param string $key The key
     * @param null|mixed $default The default value if the parameter key does not exist
     * @param bool $deep If true, a path like foo[bar] will find deeper items
     * @return mixed|null
     */
    public function getCookie($key, $default = null, $deep = false){
        return $this->cookies->get($key, $default, $deep);
    }

    /**
     * @param string $var The key
     * @param null|mixed $default The default value if the parameter key does not exist
     * @param bool $deep If true, a path like foo[bar] will find deeper items
     * @return mixed|null
     */
    public function post($var, $default = null, $deep = false) {
        return $this->request->get($var, $default, $deep);
    }

    /**
     * @param mixed $var
     * @return string|bool
     */
    public function postStr($var) {
        $v = $this->request->get($var);
        return ($v && is_array($v)) ? false : $v;
    }

    /**
     * @param mixed $var
     * @return array|bool
     */
    public function postArray($var) {
        $v = $this->request->get($var);
        return ($v && is_array($v)) ? $v : false;
    }

    /**
     * @param mixed $var
     * @return integer|bool
     */
    public function postInt($var) {
        $v = $this->request->get($var);
        return ($v && is_array($v)) ? false : intval($v);
    }

    /**
     * @static
     * @return \Framework\Common\WebRequest
     */
    public static function createFromGlobals() {
        return parent::createFromGlobals();
    }
}
