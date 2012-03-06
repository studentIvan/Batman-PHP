<?php
namespace Framework\Common\Debug;

use \Doctrine\DBAL\Logging\SQLLogger,
    \Doctrine\DBAL\Query\QueryBuilder,
    \Zend\Registry;

class DBALLogger implements SQLLogger
{
    /**
     * Logs a SQL statement somewhere.
     *
     * @param string $sql The SQL to be executed.
     * @param array $params The SQL parameters.
     * @param array $types The SQL parameter types.
     * @return void
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $registry = Registry::getInstance();
        $result = array();

        if ($sql instanceof QueryBuilder)
        {
            /**
             * @var $sql \Doctrine\DBAL\Query\QueryBuilder
             */
            $result['Database_Platform'] = $sql->getConnection()->getDatabasePlatform()->getName();
        }

        $result['SQL'] = strval($sql);
        $result['start'] = microtime(true);

        if (!isset($registry->sql_debug_data))
            $registry->sql_debug_data = array();

        $registry->sql_debug_data[] = $result;
    }

    /**
     * Mark the last started query as stopped. This can be used for timing of queries.
     *
     * @return void
     */
    public function stopQuery()
    {
        $registry = Registry::getInstance();
        end($registry->sql_debug_data);
        $key = key($registry->sql_debug_data);
        $start = $registry->sql_debug_data[$key]['start'];
        unset($registry->sql_debug_data[$key]['start']);
        $registry->sql_debug_data[$key]['Execution_time'] = number_format(microtime(true) - $start, 5) . ' ms';
    }
}
