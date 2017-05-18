<?php

namespace Magsql\Universal\Expr;

use Magsql\Driver\BaseDriver;
use Magsql\ToSqlInterface;
use Magsql\ArgumentArray;
use Magsql\Bind;

class RawExpr implements ToSqlInterface
{
    public $str;

    public $args;

    public function __construct($str, array $args = array())
    {
        $this->str = $str;
        $this->args = $args;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        foreach ($this->args as $k => $a) {
            if ($a instanceof Bind) {
                $args->add($a);
            } else {
                $args->add(new Bind($k, $a));
            }
        }

        return $this->str;
    }
}
