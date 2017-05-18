<?php

namespace Magsql\Universal\Syntax;

use Magsql\ToSqlInterface;
use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;
use Magsql\Universal\Traits\KeyTrait;

class Constraint implements ToSqlInterface
{
    use KeyTrait;

    protected $symbol;

    public function __construct($symbol = null)
    {
        $this->symbol = $symbol;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        $sql = '';
        // constrain symbol is optional but only supported by MySQL
        if ($this->symbol) {
            $sql .= 'CONSTRAINT '.$driver->quoteIdentifier($this->symbol).' ';
        }
        $sql .= $this->buildKeyClause($driver, $args);

        return $sql;
    }
}
