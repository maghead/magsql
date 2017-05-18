<?php

namespace Magsql\Universal\Expr;

use Magsql\Driver\BaseDriver;
use Magsql\ToSqlInterface;
use Magsql\ArgumentArray;

class NotInExpr extends InExpr implements ToSqlInterface
{
    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        return $this->exprStr.' NOT IN '.$this->listExpr->toSql($driver, $args);
    }
}
