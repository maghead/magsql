<?php

namespace Magsql\Universal\Expr;

use Magsql\Driver\BaseDriver;
use Magsql\ToSqlInterface;
use Magsql\ArgumentArray;

class InExpr implements ToSqlInterface
{
    public $exprStr;

    public $listExpr;

    public function __construct($exprStr, $expr)
    {
        $this->exprStr = $exprStr;
        $this->listExpr = new ListExpr($expr);
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        return $this->exprStr.' IN '.$this->listExpr->toSql($driver, $args);
    }
}
