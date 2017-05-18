<?php

namespace Magsql\Universal\Expr;

use Magsql\Driver\BaseDriver;
use Magsql\ToSqlInterface;
use Magsql\ArgumentArray;

class NotRegExpExpr extends RegExpExpr implements ToSqlInterface
{
    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        return $this->exprStr.' NOT REGEXP '.$driver->deflate($this->pat, $args);
    }
}
