<?php
namespace Framework\Common;

class SQLBuilder extends \Doctrine\DBAL\Query\QueryBuilder
{
    /**
     * @param string $from
     * @param bool|string $alias
     * @return \Framework\Common\SQLBuilder
     */
    public function from($from, $alias = false)
    {
        if (!$alias) $alias = substr($from, 0, 1);
        return parent::from($from, $alias);
    }

    /**
     * @param mixed $select
     * @return \Framework\Common\SQLBuilder
     */
    public function select($select = null)
    {
        return parent::select($select);
    }

    /**
     * @param $sort
     * @param null $order
     * @return \Framework\Common\SQLBuilder
     */
    public function orderBy($sort, $order = null)
    {
        return parent::orderBy($sort, $order);
    }
}
