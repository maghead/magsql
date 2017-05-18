<?php

namespace Magsql\Universal\Expr;

use Magsql\Driver\BaseDriver;
use Magsql\ToSqlInterface;
use Magsql\ArgumentArray;
use Magsql\Raw;
use InvalidArgumentException;

class ListExpr implements ToSqlInterface
{
    protected $expr;

    public function __construct($expr)
    {
        $this->expr = $expr;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        $sql = '';
        if (is_array($this->expr)) {
            foreach ($this->expr as $val) {
                $sql .= ','.$driver->deflate($val, $args);
            }
            $sql = ltrim($sql, ',');
        } elseif ($this->expr instanceof ToSqlInterface) {
            $sql = $driver->deflate($this->expr, $args);
        } elseif ($this->expr instanceof Raw) {
            $sql = $this->expr->__toString();
        } elseif (is_string($this->expr)) {
            $sql = $this->expr;
        } else {
            throw new InvalidArgumentException('Invalid expr type');
        }

        return '('.$sql.')';
    }
}
