<?php
namespace Framework\Common;
use \Framework\Core\Config;

class Security
{
    public $charset = 'UTF-8';
    protected $inv = array('/%0[0-8bcef]/', '/%1[0-9a-f]/', '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S');

    public function __construct()
    {
        $this->charset = Config::get('application', 'charset');
    }

    /**
     * @static
     * @param string $word
     * @return string
     */
    public static function getHash($word)
    {
        $secret = Config::get('application', 'secret');
        return md5($word . (($secret) ? $secret : 'batman'));
    }

    /**
     * @static
     * @param array $keys
     * @return string
     */
    public static function getArrayHash(array $keys)
    {
        $sum = self::getHash('');

        foreach ($keys as $key)
        {
            if ($key !== false)
                $sum .= md5(str_replace('!#!', '', $key));
        }

        return self::getHash($sum);
    }

    /**
     * Clears the string against XSS
     *
     * @param string $string
     * @param bool $antiSpy clean invisible?
     * @return string
     */
    public function clean($string, $antiSpy = true)
    {
        $string = htmlspecialchars($string, ENT_QUOTES, $this->charset);
        if ($antiSpy) $string = $this->cleanInvisible($string);
        return $string;
    }

    /**
     * @param $string
     * @param bool $antiSpy
     */
    public function referenceClean(&$string, $antiSpy = true)
    {
        $string = htmlspecialchars($string, ENT_QUOTES, $this->charset);
        if ($antiSpy) $string = $this->cleanInvisible($string);
    }

    /**
     * @param $mixed
     * @param bool $antiSpy
     * @return array|string
     */
    public function mixedClean($mixed, $antiSpy = false)
    {
        return is_array($mixed) ? $this->needArray($mixed, $antiSpy) : $this->clean($mixed, $antiSpy);
    }

    /**
     * Give array from variable
     *
     * @experimental
     * @param mixed $var
     * @param bool $clean
     * @return array
     */
    public function needArray($var, $clean = false)
    {
        if (is_array($var)) {
            if ($clean) array_walk_recursive($var, array($this, 'referenceClean'));
            return $var;
        } else {
            return (is_string($var) || is_numeric($var)) ?
                array((($clean) ? $this->clean($var) : $var)) : array();
        }
    }

    /**
     * Detect and remove invisible characters in string
     *
     * @experimental
     * @param string $str
     * @return string
     */
    public function cleanInvisible($str)
    {
        do $str = preg_replace($this->inv, '', $str, -1, $c); while ($c);
        return $str;
    }
}
