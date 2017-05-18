<?php

namespace Magsql\Universal\Expr;

use Magsql\Driver\BaseDriver;
use Magsql\ToSqlInterface;
use Magsql\ArgumentArray;

class IsNotExpr extends IsExpr implements ToSqlInterface
{
    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        return $this->exprStr.' IS NOT '.$driver->deflate($this->boolean, $args);
    }
}
