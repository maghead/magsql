<?php

namespace Magsql\Universal\Syntax;

use Magsql\ToSqlInterface;
use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;

class AlterTableDropForeignKey implements ToSqlInterface
{
    protected $fkSymbol;

    public function __construct($fkSymbol)
    {
        $this->fkSymbol = $fkSymbol;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        return 'DROP FOREIGN KEY '.$driver->quoteIdentifier($this->fkSymbol);
    }
}
