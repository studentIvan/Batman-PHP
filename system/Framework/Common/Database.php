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
        $cfg = new Configuration();
        return DriverManager::getConnection(Config::get($appConfig), $cfg);
    }

    /**
     * @static
     * @return string
     */
    public static function getDateTime() {
        $dateTime = new \DateTime('now');
        return $dateTime->format('Y-m-d H:i:sP');
    }
}
