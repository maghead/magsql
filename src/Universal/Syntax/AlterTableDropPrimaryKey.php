<?php

namespace Magsql\Universal\Syntax;

use Magsql\ToSqlInterface;
use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;

class AlterTableDropPrimaryKey implements ToSqlInterface
{
    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        return 'DROP PRIMARY KEY';
    }
}
