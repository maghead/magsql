<?php
use Magsql\Raw;
use Magsql\Query\UpdateQuery;
use Magsql\Query\DeleteQuery;
use Magsql\Driver\MySQLDriver;
use Magsql\Driver\PgSQLDriver;
use Magsql\Driver\SQLiteDriver;
use Magsql\Universal\Expr\FuncCallExpr;
use Magsql\ToSqlInterface;
use Magsql\ArgumentArray;
use Magsql\Bind;

class FuncCallExprTest extends \PHPUnit\Framework\TestCase
{
    public function testFuncCall()
    {
        $driver = new MySQLDriver;
        $args = new ArgumentArray;
        $func = new FuncCallExpr('COUNT', [ new Raw('*') ]);
        $sql = $func->toSql($driver, $args);
        $this->assertEquals('COUNT(*)', $sql);
    }
}

