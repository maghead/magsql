<?php

namespace Magsql\Universal\Query;

use Exception;
use Magsql\Exception\UnsupportedDriverException;
use Magsql\Driver\BaseDriver;
use Magsql\Driver\MySQLDriver;
use Magsql\Driver\PgSQLDriver;
use Magsql\Driver\SQLiteDriver;
use Magsql\ToSqlInterface;
use Magsql\ArgumentArray;
use Magsql\Universal\Syntax\Conditions;
use Magsql\Universal\Traits\OrderByTrait;
use Magsql\Universal\Traits\WhereTrait;
use Magsql\Universal\Traits\PagingTrait;
use Magsql\Universal\Expr\SelectExpr;
use Magsql\MySQL\Traits\PartitionTrait;
use Magsql\MySQL\Traits\IndexHintTrait;
use Magsql\Universal\Traits\JoinTrait;
use Magsql\Universal\Traits\OptionTrait;

class UUIDQuery implements ToSqlInterface
{
    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        if ($driver instanceof MySQLDriver) {
            return 'SELECT UUID();';
        }
        if ($driver instanceof PgSQLDriver) {
            return 'SELECT UUID_GENERATE_V4();';
        }
        if ($driver instanceof SQLiteDriver) {
            return 'SELECT hex(randomblob(16));';
        }
        throw new UnsupportedDriverException($driver, $this);
    }
}
