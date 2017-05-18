<?php

namespace Magsql\Universal\Query;

use Magsql\ToSqlInterface;
use Magsql\ArgumentArray;
use Magsql\Driver\BaseDriver;
use Magsql\Driver\MySQLDriver;
use Magsql\Driver\PgSQLDriver;
use Magsql\Exception\IncompleteSettingsException;
use Magsql\PgSQL\Traits\ConcurrentlyTrait;
use Magsql\Universal\Traits\IfExistsTrait;
use Magsql\Universal\Traits\RestrictTrait;
use Magsql\Universal\Traits\CascadeTrait;

/**
 
 */
class DropIndexQuery implements ToSqlInterface
{
    use ConcurrentlyTrait;
    use IfExistsTrait;
    use CascadeTrait;
    use RestrictTrait;

    protected $indexName;

    protected $tableName;

    /**
     * MySQL.
     */
    protected $lockType;

    /**
     * MySQL.
     */
    protected $algorithm;

    public function drop($indexName)
    {
        $this->indexName = $indexName;

        return $this;
    }

    public function on($tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * MySQL 5.6.6.
     *
     * valid values: {DEFAULT|NONE|SHARED|EXCLUSIVE}
     */
    public function lock($lockType)
    {
        $this->lockType = $lockType;

        return $this;
    }

    /**
     * MySQL 5.6.6.
     *
     * valid values: {DEFAULT|INPLACE|COPY}
     */
    public function algorithm($algorithm)
    {
        $this->algorithm = $algorithm;

        return $this;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        $sql = 'DROP INDEX';

        if ($driver instanceof PgSQLDriver) {
            $sql .= $this->buildConcurrentlyClause($driver, $args);
        }

        $sql .= ' '.$driver->quoteIdentifier($this->indexName);

        $sql .= $this->buildIfExistsClause($driver, $args);

        if ($driver instanceof MySQLDriver) {
            if (!$this->tableName) {
                throw new IncompleteSettingsException('tableName is required. Use on($tableName) to specify one.');
            }
            $sql .= ' ON '.$driver->quoteIdentifier($this->tableName);

            if ($this->lockType) {
                $sql .= ' LOCK = '.$this->lockType;
            }
            if ($this->algorithm) {
                $sql .= ' ALGORITHM = '.$this->algorithm;
            }
        }

        if ($driver instanceof PgSQLDriver) {
            $sql .= $this->buildCascadeClause();
            $sql .= $this->buildRestrictClause();
        }

        return $sql;
    }
}
