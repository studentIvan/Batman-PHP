<?php
namespace Framework\Core;
use \Symfony\Component\Yaml\Yaml;
use \Symfony\Component\Yaml\Exception\ParseException;

class Config {
    /**
     * @var array
     */
    protected static $data = array();

    /**
     * @var bool
     */
    protected static $resourcesLoaded = false;

    /**
     * @static
     * @return void
     */
    public static function init()
    {
        try
        {
            self::$data = Yaml::parse('app\config\config.yml');

            if (isset(self::$data['application']['environment']))
            {
                self::$data = array_replace_recursive(
                    self::$data, Yaml::parse(
                        'app\config\config_' . self::$data['application']['environment'] . '.yml'
                    )
                );
            }

            self::phpConfigure();
        }
        catch (ParseException $e)
        {
            echo $e->getMessage();
            exit;
        }
    }

    /**
     * @static
     * @return void
     */
    public static function loadResources() {
        if (!self::$resourcesLoaded) {
            self::$data = array_replace_recursive(self::$data, Yaml::parse('app\config\resources.yml'));
            self::$resourcesLoaded = true;
        }
    }

    /**
     * @static
     * @param string $config
     * @param bool|string $subconfig
     * @return bool|array
     */
    public static function get($config, $subconfig = false) {
        return ($subconfig) ?
            (isset(self::$data[$config][$subconfig]) ? self::$data[$config][$subconfig] : false) :
            (isset(self::$data[$config]) ? self::$data[$config] : false);
    }

    /**
     * @static
     * @return void
     */
    public static function phpConfigure() {
        if (self::get('framework', 'timezone'))
            date_default_timezone_set(self::get('framework', 'timezone'));
        if (self::get('framework', 'phpdebug') == false) {
            error_reporting(0);
            //ini_set('display_errors', 'Off');
        } else {
            error_reporting(-1);
            //ini_set('display_errors', 'On');
        }
    }
}