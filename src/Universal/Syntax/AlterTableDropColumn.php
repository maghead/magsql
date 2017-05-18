<?php

namespace Magsql\Universal\Syntax;

use Magsql\ToSqlInterface;
use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;

class AlterTableDropColumn implements ToSqlInterface
{
    protected $column;

    public function __construct(Column $column)
    {
        $this->column = $column;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        $sql = 'DROP COLUMN ';
        $sql .= $driver->quoteIdentifier($this->column->name);

        return $sql;
    }
}
