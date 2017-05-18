<?php

namespace Magsql\Universal\Syntax;

use Magsql\ToSqlInterface;
use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;

class AlterTableDropIndex implements ToSqlInterface
{
    protected $index;

    public function __construct($indexName)
    {
        $this->indexName = $indexName;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        return 'DROP INDEX '.$driver->quoteIdentifier($this->indexName);
    }
}
