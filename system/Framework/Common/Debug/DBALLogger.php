<?php
namespace Framework\Common\Debug;
use \Doctrine\DBAL\Logging\SQLLogger;
use \Zend\Registry;

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
        $registry->debugger_sql_counter++;
        /*$params = $params || array();
        $types = $types || array();

        if (!isset($registry->sql_debug_data))
        {
            $registry->sql_debug_data = array(json_encode(array(
                'query' => $sql,
                'params' => $params,
                'types' => $types,
            )));
        }
        else
        {
            $registry->sql_debug_data[] = json_encode(array(
                'query' => $sql,
                'params' => $params,
                'types' => $types,
            ));
        }*/

    }

    /**
     * Mark the last started query as stopped. This can be used for timing of queries.
     *
     * @return void
     */
    public function stopQuery()
    {

    }
}
