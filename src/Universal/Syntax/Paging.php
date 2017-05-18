<?php

namespace Magsql\Universal\Syntax;

use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;
use Magsql\ToSqlInterface;

class Paging implements ToSqlInterface
{
    public $limit;

    public $offset;

    public function __construct()
    {
    }

    public function offset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function page($page, $pageSize)
    {
        if ($page > 1) {
            $this->offset(($page - 1) * $pageSize);
        }

        return $this->limit($pageSize);
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        $sql = '';
        if ($this->limit) {
            $sql .= ' LIMIT '.intval($this->limit);
        }
        if ($this->offset) {
            $sql .= ' OFFSET '.intval($this->offset);
        }

        return $sql;
    }
}
