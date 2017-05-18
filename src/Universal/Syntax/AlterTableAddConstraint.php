<?php

namespace Magsql\Universal\Syntax;

use Magsql\ToSqlInterface;
use Magsql\Driver\BaseDriver;
use Magsql\Universal\Traits\KeyTrait;
use Magsql\ArgumentArray;

class AlterTableAddConstraint implements ToSqlInterface
{
    use KeyTrait;

    protected $constraint;

    public function constraint($symbol)
    {
        return $this->constraint = new Constraint($symbol, $this);
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        if ($this->constraint) {
            return 'ADD '.$this->constraint->toSql($driver, $args);
        }

        return 'ADD '.$this->buildKeyClause($driver, $args);
    }
}
