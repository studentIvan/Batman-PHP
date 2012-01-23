<?php
namespace Framework\Common;
use \Symfony\Component\HttpFoundation\Request;

class WebRequest extends Request
{
    /**
     * @param string $var The key
     * @param null|mixed $default The default value if the parameter key does not exist
     * @param bool $deep If true, a path like foo[bar] will find deeper items
     * @return mixed|null
     */
    public function post($var, $default = null, $deep = false) {
        return $this->request->get($var, $default, $deep);
    }
}
