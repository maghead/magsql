<?php

namespace Magsql\Universal\Syntax;

use Magsql\ToSqlInterface;
use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;

class AlterTableDrop implements ToSqlInterface
{
    protected $subquery;

    public function __construct($anything)
    {
        $this->subquery = $anything;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        if ($this->subquery instanceof ToSqlInterface) {
            return 'DROP '.$this->subquery->toSql($driver, $args);
        }

        return 'DROP '.$this->subquery;
    }
}
