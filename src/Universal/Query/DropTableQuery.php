<?php

namespace Magsql\Universal\Query;

use Magsql\ToSqlInterface;
use Magsql\ArgumentArray;
use Magsql\Driver\BaseDriver;
use Magsql\Driver\MySQLDriver;
use Magsql\Driver\PgSQLDriver;
use Magsql\PgSQL\Traits\ConcurrentlyTrait;
use Magsql\Universal\Traits\IfExistsTrait;
use Magsql\Universal\Traits\RestrictTrait;
use Magsql\Universal\Traits\CascadeTrait;

/**
 * MySQL Drop table syntax.
 */
class DropTableQuery implements ToSqlInterface
{
    use ConcurrentlyTrait;
    use IfExistsTrait;
    use CascadeTrait;
    use RestrictTrait;

    protected $tableNames = array();

    protected $temporary;

    public function __construct($tableNames = null)
    {
        if ($tableNames && is_array($tableNames)) {
            $this->tableNames = $tableNames;
        } elseif (is_string($tableNames)) {
            $this->tableNames = array($tableNames);
        }
    }

    public function drop($tableName)
    {
        $this->tableNames[] = $tableName;

        return $this;
    }

    public function temporary()
    {
        $this->temporary = true;

        return $this;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        $sql = 'DROP';

        // only for mysql
        if ($driver instanceof MySQLDriver && $this->temporary) {
            $sql .= ' TEMPORARY';
        }

        $sql .= ' TABLE';

        if ($driver instanceof PgSQLDriver) {
            $sql .= $this->buildConcurrentlyClause($driver, $args);
        }

        $sql .= $this->buildIfExistsClause($driver, $args);

        foreach ($this->tableNames as $tableName) {
            $sql .= ' '.$driver->quoteIdentifier($tableName).',';
        }
        $sql = rtrim($sql, ',');

        if ($driver instanceof PgSQLDriver) {
            $sql .= $this->buildCascadeClause();
            $sql .= $this->buildRestrictClause();
        }

        return $sql;
    }
}
