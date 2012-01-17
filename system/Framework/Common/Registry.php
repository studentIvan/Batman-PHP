<?php
namespace Framework\Common;

/**
 * Common registry
 */
class Registry
{
    /**
     * @var array
     */
    protected static $store = array();

    /**
     * Set registry variable
     *
     * @static
     * @param string $var
     * @param mixed $data
     * @return void
     */
    public static function set($var, $data) {
        self::$store[$var] = $data;
    }

    /**
     * Get registry variable
     *
     * @static
     * @param string $var
     * @return mixed
     */
    public static function get($var) {
        return self::$store[$var];
    }
}
