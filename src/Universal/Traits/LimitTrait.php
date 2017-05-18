<?php

namespace Magsql\Universal\Traits;

use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;

trait LimitTrait
{
    protected $limit;

    /********************************************************
     * LIMIT clauses
     *******************************************************/
    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function buildLimitClause(BaseDriver $driver, ArgumentArray $args)
    {
        if ($this->limit) {
            return ' LIMIT '.intval($this->limit);
        }

        return '';
    }
}
