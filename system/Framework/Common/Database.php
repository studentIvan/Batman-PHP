<?php
namespace Framework\Common;

use \Framework\Core\Config,
    \Doctrine\DBAL\Configuration,
    \Doctrine\DBAL\DriverManager,
    \Framework\Common\Debug\DBALLogger,
    \Zend\Registry;

class Database
{
    /**
     * @static
     * @param string $appConfig
     * @return \Doctrine\DBAL\Connection
     */
    public static function getInstance($appConfig = 'database')
    {
        $registry = Registry::getInstance();

        if (!isset($registry["dbc_$appConfig"]))
        {
            $rtmConfig = new Configuration();

            if (Config::get('framework', 'debug_toolbar'))
            {
                $rtmConfig->setSQLLogger(new DBALLogger());
            }

            $registry["dbc_$appConfig"] = DriverManager::getConnection(Config::get($appConfig), $rtmConfig);
        }

        return $registry["dbc_$appConfig"];
    }

    /**
     * @static
     * @param string $appConfig
     * @return \Doctrine\DBAL\Connection
     */
    public static function newFreeInstance($appConfig = 'database')
    {
        $config = Config::get($appConfig);
        unset($config['dbname']);
        return DriverManager::getConnection($config);
    }
}
