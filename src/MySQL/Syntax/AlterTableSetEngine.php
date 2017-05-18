<?php

namespace Magsql\MySQL\Syntax;

use Magsql\ToSqlInterface;
use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;

class AlterTableSetEngine implements ToSqlInterface
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        return 'ENGINE = '.$driver->deflate($this->value);
    }
}
