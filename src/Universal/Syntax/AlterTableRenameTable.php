<?php

namespace Magsql\Universal\Syntax;

use Magsql\ToSqlInterface;
use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;

class AlterTableRenameTable implements ToSqlInterface
{
    protected $toTable;

    public function __construct($toTable)
    {
        $this->toTable = $toTable;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        return 'RENAME TO '.$driver->quoteIdentifier($this->toTable);
    }
}
