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

    /**
     * @param string|integer $key The parameter position or name.
     * @param mixed $value The parameter value.
     * @param string|null $type PDO::PARAM_*
     * @return \Framework\Common\SQLBuilder
     */
    public function setParameter($key, $value, $type = null)
    {
        return parent::setParameter($key, $value, $type);
    }

    /**
     * @param array $params
     * @param array $types
     * @return \Framework\Common\SQLBuilder
     */
    public function setParameters(array $params, array $types = array())
    {
        return parent::setParameters($params, $types);
    }

    /**
     * @param $firstResult
     * @return \Framework\Common\SQLBuilder
     */
    public function setFirstResult($firstResult)
    {
        return parent::setFirstResult($firstResult);
    }

    /**
     * @param $maxResults
     * @return \Framework\Common\SQLBuilder
     */
    public function setMaxResults($maxResults)
    {
        return parent::setMaxResults($maxResults);
    }

    /**
     * @param string $sqlPartName
     * @param string $sqlPart
     * @param bool|string $append
     * @return \Framework\Common\SQLBuilder
     */
    public function add($sqlPartName, $sqlPart, $append = false)
    {
        return parent::add($sqlPartName, $sqlPart, $append);
    }

    /**
     * @param mixed $select The selection expression.
     * @return \Framework\Common\SQLBuilder
     */
    public function addSelect($select = null)
    {
        return parent::addSelect($select);
    }

    /**
     * @param mixed $delete
     * @param mixed $alias
     * @return \Framework\Common\SQLBuilder
     */
    public function delete($delete = null, $alias = null)
    {
        return parent::delete($delete, $alias);
    }

    /**
     * @param mixed $update
     * @param mixed $alias
     * @return \Framework\Common\SQLBuilder
     */
    public function update($update = null, $alias = null)
    {
        return parent::update($update, $alias);
    }

    /**
     * @param $fromAlias
     * @param $join
     * @param $alias
     * @param mixed $condition
     * @return \Framework\Common\SQLBuilder
     */
    public function join($fromAlias, $join, $alias, $condition = null)
    {
        return parent::join($fromAlias, $join, $alias, $condition);
    }

    /**
     * @param $fromAlias
     * @param $join
     * @param $alias
     * @param mixed $condition
     * @return \Framework\Common\SQLBuilder
     */
    public function innerJoin($fromAlias, $join, $alias, $condition = null)
    {
        return parent::innerJoin($fromAlias, $join, $alias, $condition);
    }

    /**
     * @param $fromAlias
     * @param $join
     * @param $alias
     * @param mixed $condition
     * @return \Framework\Common\SQLBuilder
     */
    public function leftJoin($fromAlias, $join, $alias, $condition = null)
    {
        return parent::leftJoin($fromAlias, $join, $alias, $condition);
    }

    /**
     * @param $fromAlias
     * @param $join
     * @param $alias
     * @param mixed $condition
     * @return \Framework\Common\SQLBuilder
     */
    public function rightJoin($fromAlias, $join, $alias, $condition = null)
    {
        return parent::rightJoin($fromAlias, $join, $alias, $condition);
    }

    /**
     * @param $key
     * @param $value
     * @return \Framework\Common\SQLBuilder
     */
    public function set($key, $value)
    {
        return parent::set($key, $value);
    }

    /**
     * @param $predicates
     * @return \Framework\Common\SQLBuilder
     */
    public function where($predicates)
    {
        return parent::where($predicates);
    }

    /**
     * @param $where
     * @return \Framework\Common\SQLBuilder
     */
    public function andWhere($where)
    {
        return parent::andWhere($where);
    }

    /**
     * @param $where
     * @return \Framework\Common\SQLBuilder
     */
    public function orWhere($where)
    {
        return parent::orWhere($where);
    }

    /**
     * @param $groupBy
     * @return \Framework\Common\SQLBuilder
     */
    public function groupBy($groupBy)
    {
        return parent::groupBy($groupBy);
    }

    /**
     * @param $groupBy
     * @return \Framework\Common\SQLBuilder
     */
    public function addGroupBy($groupBy)
    {
        return parent::addGroupBy($groupBy);
    }

    /**
     * @param $having
     * @return \Framework\Common\SQLBuilder
     */
    public function having($having)
    {
        return parent::having($having);
    }

    /**
     * @param $having
     * @return \Framework\Common\SQLBuilder
     */
    public function andHaving($having)
    {
        return parent::andHaving($having);
    }

    /**
     * @param $having
     * @return \Framework\Common\SQLBuilder
     */
    public function orHaving($having)
    {
        return parent::orHaving($having);
    }

    /**
     * @param $sort
     * @param mixed $order
     * @return \Framework\Common\SQLBuilder
     */
    public function addOrderBy($sort, $order = null)
    {
        return parent::addOrderBy($sort, $order);
    }
}
