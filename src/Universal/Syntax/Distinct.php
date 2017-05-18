<?php

namespace Magsql\Universal\Syntax;

use Magsql\ToSqlInterface;
use Magsql\ArgumentArray;
use Magsql\Driver\BaseDriver;
use Exception;

class Distinct implements ToSqlInterface
{
    protected $expr;

    public function __construct($expr)
    {
        $this->expr = $expr;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        if ($this->expr instanceof ToSqlInterface) {
            return 'DISTINCT '.$this->expr->toSql($driver, $args);
        } elseif (is_string($this->expr)) {
            return 'DISTINCT '.$this->expr;
        } else {
            throw new Exception('Unsupported expression type');
        }
    }
}
