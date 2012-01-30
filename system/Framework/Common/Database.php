<?php
namespace Framework\Common;
use \Framework\Core\Config;
use \Doctrine\DBAL\Configuration;
use \Doctrine\DBAL\DriverManager;

/**
 * @link http://www.doctrine-project.org/docs/dbal/2.1/en/reference/data-retrieval-and-manipulation.html
 */
class Database
{
    /**
     * @static
     * @param string $appConfig
     * @return \Doctrine\DBAL\Connection
     */
    public static function newInstance($appConfig = 'database') {
        return DriverManager::getConnection(Config::get($appConfig));
    }

    /**
     * @static
     * @param string $appConfig
     * @return \Doctrine\DBAL\Connection
     */
    public static function newNoDbInstance($appConfig = 'database') {
        $config = Config::get($appConfig);
        unset($config['dbname']);
        return DriverManager::getConnection($config);
    }

    /**
     * @static
     * @param string $time
     * @return string
     */
    public static function getDateTime($time = 'now') {
        $dateTime = new \DateTime($time);
        return $dateTime->format('Y-m-d H:i:sP');
    }
}
