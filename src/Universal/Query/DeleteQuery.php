<?php

namespace Magsql\Universal\Query;

use Magsql\Driver\BaseDriver;
use Magsql\Driver\MySQLDriver;
use Magsql\ToSqlInterface;
use Magsql\ArgumentArray;
use Magsql\MySQL\Traits\PartitionTrait;
use Magsql\Universal\Traits\OrderByTrait;
use Magsql\Universal\Traits\JoinTrait;
use Magsql\Universal\Traits\OptionTrait;
use Magsql\Universal\Traits\WhereTrait;
use Magsql\Universal\Traits\LimitTrait;
use Magsql\Exception\IncompleteSettingsException;

/**
 * Delete Statement Query.
 *
 * @code
 *
 *  $query = new Magsql\Universal\Query\DeleteQuery;
 *  $query->delete(array(
 *      'name' => 'foo',
 *      'values' => 'bar',
 *  ));
 *  $sql = $query->toSql($driver, $args);
 *
 * @code
 *
 * The fluent interface rules of Query objects
 *
 *    1. setters should return self, since there is no return value.
 *    2. getters should be just what they are.
 *    3. modifier can set / append data and return self
 */
class DeleteQuery implements ToSqlInterface
{
    use OptionTrait;
    use JoinTrait;
    use WhereTrait;
    use LimitTrait;
    use PartitionTrait;
    use OrderByTrait;

    protected $deleteTables = array();

    public function from($table, $alias = null)
    {
        return $this->delete($table, $alias);
    }

    /**
     * ->delete('posts', 'p')
     * ->delete('users', 'u').
     */
    public function delete($table, $alias = null)
    {
        if ($alias) {
            $this->deleteTables[$table] = $alias;
        } else {
            $this->deleteTables[] = $table;
        }

        return $this;
    }

    /****************************************************************
     * Builders
     ***************************************************************/
    public function buildFromClause(BaseDriver $driver, ArgumentArray $args)
    {
        if (empty($this->deleteTables)) {
            throw new IncompleteSettingsException('DeleteQuery requires tables to delete.');
        }

        $tableRefs = array();
        foreach ($this->deleteTables as $k => $v) {
            /* "column AS alias" OR just "column" */
            if (is_string($k)) {
                $sql = $driver->quoteTable($k).' AS '.$v;
                $tableRefs[] = $sql;
            } elseif (is_integer($k) || is_numeric($k)) {
                $sql = $driver->quoteTable($v);
                $tableRefs[] = $sql;
            }
        }

        return ' FROM '.implode(', ', $tableRefs);
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        $sql = 'DELETE'
            .$this->buildOptionClause()
            .$this->buildFromClause($driver, $args)
            ;

        if ($driver instanceof MySQLDriver) {
            $sql .= $this->buildPartitionClause($driver, $args);
        }

        $sql .= $this->buildJoinClause($driver, $args)
            .$this->buildWhereClause($driver, $args)
            ;

        if ($driver instanceof MySQLDriver) {
            $sql .= $this->buildOrderByClause($driver, $args);
            $sql .= $this->buildLimitClause($driver, $args);
        }

        return $sql;
    }

    public function __clone()
    {
        $this->where = $this->where;
    }
}
