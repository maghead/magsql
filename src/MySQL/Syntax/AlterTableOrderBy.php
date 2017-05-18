<?php

namespace Magsql\MySQL\Syntax;

use Magsql\ToSqlInterface;
use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;
use Magsql\Universal\Syntax\ColumnNames;

class AlterTableOrderBy implements ToSqlInterface
{
    protected $columnNames;

    public function __construct(array $orders)
    {
        $this->columnNames = new ColumnNames($orders);
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        return 'ORDER BY '.$this->columnNames->toSql($driver, $args);
    }
}
