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

    public static function getHash($word)
    {
        $secret = Config::get('application', 'secret');
        return md5($word . (($secret) ? $secret : 'batman'));
    }

    /**
     * Clears the string against XSS
     *
     * @param string $string
     * @param bool $antiSpy clean invisible?
     * @return string
     */
    public function clean($string, $antiSpy = false)
    {
        $cleaned = htmlspecialchars($string, ENT_QUOTES, $this->charset);
        if ($antiSpy) $cleaned = $this->cleanInvisible($cleaned);
        return $cleaned;
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
            if ($clean) array_walk_recursive($var, array($this, 'clean'));
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
