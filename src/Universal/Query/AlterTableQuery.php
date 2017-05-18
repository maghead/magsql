<?php

namespace Magsql\Universal\Query;

use Magsql\Driver\BaseDriver;
use Magsql\ToSqlInterface;
use Magsql\ArgumentArray;
use Magsql\Universal\Syntax\Column;
use Magsql\Universal\Syntax\AlterTableAddConstraint;
use Magsql\Universal\Syntax\AlterTableRenameColumn;
use Magsql\Universal\Syntax\AlterTableChangeColumn;
use Magsql\Universal\Syntax\AlterTableAddColumn;
use Magsql\Universal\Syntax\AlterTableDropColumn;
use Magsql\Universal\Syntax\AlterTableRenameTable;
use Magsql\Universal\Syntax\AlterTableModifyColumn;
use Magsql\Universal\Syntax\AlterTableDropPrimaryKey;
use Magsql\Universal\Syntax\AlterTableDropForeignKey;
use Magsql\Universal\Syntax\AlterTableDropIndex;
use Magsql\Universal\Syntax\AlterTableAdd;
use Magsql\Exception\CriticalIncompatibleUsageException;
use Magsql\MySQL\Syntax\AlterTableOrderBy;
use Magsql\SyntaxExtender;

class AlterTableQuery implements ToSqlInterface
{
    protected $table;

    protected $specs = array();

    use SyntaxExtender;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function add($subquery = null)
    {
        if ($subquery) {
            return $this->specs[] = new AlterTableAdd($subquery);
        } else {
            return $this->specs[] = new AlterTableAddConstraint();
        }
    }

    public function modifyColumn(Column $column)
    {
        $this->specs[] = $spec = new AlterTableModifyColumn($column);

        return $spec;
    }

    /**
     * @param string|Column $oldColumn
     * @param Column        $newColumn
     */
    public function changeColumn($oldColumn, Column $newColumn)
    {
        $this->specs[] = $spec = new AlterTableChangeColumn($oldColumn, $newColumn);

        return $spec;
    }

    /**
     * Rename table column.
     *
     * @param string $fromColumn
     * @param Column $toColumn
     */
    public function renameColumn($fromColumn, Column $toColumn)
    {
        $this->specs[] = $spec = new AlterTableRenameColumn($fromColumn, $toColumn);

        return $spec;
    }

    public function addColumn(Column $toColumn)
    {
        $this->specs[] = $spec = new AlterTableAddColumn($toColumn);

        return $spec;
    }

    public function dropColumnByName($columnName)
    {
        $column = new Column($columnName);

        return $this->dropColumn($column);
    }

    public function dropColumn(Column $column)
    {
        // throw new CriticalIncompatibleUsageException('Argument must be `Column` or string');
        $this->specs[] = $spec = new AlterTableDropColumn($column);

        return $spec;
    }

    public function dropIndex($indexName)
    {
        $this->specs[] = $spec = new AlterTableDropIndex($indexName);

        return $spec;
    }

    public function dropForeignKey($fkSymbol)
    {
        $this->specs[] = $spec = new AlterTableDropForeignKey($fkSymbol);

        return $spec;
    }

    public function dropPrimaryKey()
    {
        $this->specs[] = $spec = new AlterTableDropPrimaryKey();

        return $spec;
    }

    public function orderBy(array $columnNames)
    {
        $this->specs[] = $spec = new AlterTableOrderBy($columnNames);

        return $spec;
    }

    public function __call($method, $arguments)
    {
        return $this->specs[] = $this->handleSyntax($method, $arguments);
    }

    /**
     * Rename Table.
     *
     * @param string $toTable table name
     *
     * @api
     */
    public function rename($toTable)
    {
        $this->specs[] = $spec = new AlterTableRenameTable($toTable);

        return $spec;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        $sql = 'ALTER TABLE '.$driver->quoteIdentifier($this->table).' ';
        $alterSpecSqls = array();

        foreach ($this->specs as $spec) {
            $alterSpecSqls[] = $spec->toSql($driver, $args);
        }
        $sql .= implode(",\n  ", $alterSpecSqls);

        return $sql;
    }
}
