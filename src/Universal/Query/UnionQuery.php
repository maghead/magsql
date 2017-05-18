<?php

namespace Magsql\Universal\Query;

use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;
use Magsql\ToSqlInterface;

class UnionQuery implements ToSqlInterface
{
    public $queries = array();

    public function union(SelectQuery $query)
    {
        $args = func_get_args();
        $this->queries = $this->queries + $args;

        return $this;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        $subqueries = array();
        foreach ($this->queries as $q) {
            $subqueries[] = '('.$q->toSql($driver, $args).')';
        }

        return implode(' UNION ', $subqueries);
    }
}
