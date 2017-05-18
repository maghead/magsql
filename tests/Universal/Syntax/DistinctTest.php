<?php
use Magsql\Universal\Query\CreateTableQuery;
use Magsql\Universal\Query\DropTableQuery;
use Magsql\Universal\Query\AlterTableQuery;
use Magsql\Testing\PDOQueryTestCase;
use Magsql\Driver\MySQLDriver;
use Magsql\Driver\PgSQLDriver;
use Magsql\Driver\SQLiteDriver;
use Magsql\ArgumentArray;
use Magsql\Raw;
use Magsql\Universal\Syntax\Column;
use Magsql\Universal\Syntax\Distinct;
use Magsql\Universal\Expr\FuncCallExpr;

class DistinctTest extends PDOQueryTestCase
{
    public function test()
    {
        $driver = new MySQLDriver;
        $args = new ArgumentArray;
        $expr = new Distinct(new FuncCallExpr('SUM', [ new Raw('*')]));
        $sql = $expr->toSql($driver, $args);
        is('DISTINCT SUM(*)', $sql);
    }

    /**
     * @expectedException Exception
     */
    public function testUnknownType()
    {
        $driver = new MySQLDriver;
        $args = new ArgumentArray;
        $expr = new Distinct(false);
        $sql = $expr->toSql($driver, $args);
        is('DISTINCT SUM(*)', $sql);
    }
}

