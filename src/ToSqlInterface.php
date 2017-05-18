<?php

namespace Magsql;

use Magsql\Driver\BaseDriver;

interface ToSqlInterface
{
    public function toSql(BaseDriver $driver, ArgumentArray $args);
}
