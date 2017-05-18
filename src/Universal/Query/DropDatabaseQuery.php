<?php

namespace Magsql\Universal\Query;

use Magsql\ToSqlInterface;
use Magsql\ArgumentArray;
use Magsql\Driver\BaseDriver;
use Magsql\Driver\MySQLDriver;
use Magsql\Universal\Traits\IfExistsTrait;

class DropDatabaseQuery implements ToSqlInterface
{
    use IfExistsTrait;

    protected $dbName;

    public function __construct($name = null)
    {
        $this->dbName = $name;
    }

    public function drop($name)
    {
        $this->dbName = $name;

        return $this;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        $sql = 'DROP DATABASE';
        if ($driver instanceof MySQLDriver) {
            $sql .= $this->buildIfExistsClause();
        }
        $sql .= ' '.$driver->quoteIdentifier($this->dbName);

        return $sql;
    }
}
