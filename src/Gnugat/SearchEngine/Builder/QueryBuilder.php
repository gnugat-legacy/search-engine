<?php

/*
 * This file is part of the gnugat/search-engine package.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\SearchEngine\Builder;

use Gnugat\SearchEngine\Fetcher;

class QueryBuilder
{
    /**
     * @var Fetcher
     */
    private $fetcher;

    /**
     * @var array
     */
    private $query = array();

    /**
     * @var array
     */
    private $parameters = array();

    /**
     * @param Fetcher $fetcher
     */
    public function __construct(Fetcher $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    /**
     * @param string $select
     */
    public function addSelect($select)
    {
        $this->query['select'][] = $select;
    }

    public function resetSelect()
    {
        unset($this->query['select']);
    }

    /**
     * @param string $from
     */
    public function from($from)
    {
        $this->query['from'] = $from;
    }

    public function addJoin($join, $relation, $on = null)
    {
        $join = $join.' '.$relation;
        if (null !== $on) {
            $join .= ' ON '.$on;
        }
        $this->query['join'][] = $join;
    }

    /**
     * @param string $where
     */
    public function addWhere($where)
    {
        $this->query['where'][] = $where;
    }

    /**
     * @param mixed  $name
     * @param string $value
     * @param string $type
     */
    public function addParameter($name, $value, $type)
    {
        $this->parameters[] = array(
            'name' => $name,
            'value' => $value,
            'type' => $type,
        );
    }

    /**
     * @param string $field
     * @param string $direction
     */
    public function addOrderBy($field, $direction)
    {
        $this->query['order_by'][] = $field.' '.$direction;
    }

    /**
     * @param int $limit
     */
    public function limit($limit)
    {
        $this->query['limit'] = $limit;
    }

    /**
     * @param int $offset
     */
    public function offset($offset)
    {
        $this->query['offset'] = $offset;
    }

    /**
     * @return mixed
     */
    public function fetchAll()
    {
        return $this->fetcher->fetchAll($this->buildSql(), $this->parameters);
    }

    /**
     * @return mixed
     */
    public function fetchFirst()
    {
        return $this->fetcher->fetchFirst($this->buildSql(), $this->parameters);
    }

    /**
     * @return string
     */
    private function buildSql()
    {
        if (false === isset($this->query['select'])) {
            $this->query['select'][] = '*';
        }
        $sql = 'SELECT '.implode(', ', $this->query['select']);
        $sql .= ' FROM '.$this->query['from'];
        if (true === isset($this->query['join'])) {
            $sql .= ' '.implode(' ', $this->query['join']);
        }
        if (true === isset($this->query['where'])) {
            $sql .= ' WHERE '.implode(' AND ', $this->query['where']);
        }
        if (true === isset($this->query['order_by'])) {
            $sql .= ' ORDER BY '.implode(', ', $this->query['order_by']);
        }
        if (true === isset($this->query['limit'])) {
            $sql .= ' LIMIT '.$this->query['limit'];
        }
        if (true === isset($this->query['offset'])) {
            $sql .= ' OFFSET '.$this->query['offset'];
        }

        return $sql;
    }
}
