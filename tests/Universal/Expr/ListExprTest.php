<?php
use Magsql\Driver\MySQLDriver;
use Magsql\Driver\PgSQLDriver;
use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;
use Magsql\MySQL\Query\ExplainQuery;
use Magsql\Testing\QueryTestCase;
use Magsql\Testing\PDOQueryTestCase;
use Magsql\Universal\Query\SelectQuery;
use Magsql\Universal\Expr\FuncCallExpr;
use Magsql\Universal\Query\CreateTableQuery;
use Magsql\Universal\Query\DropTableQuery;
use Magsql\Universal\Expr\ListExpr;
use Magsql\Bind;
use Magsql\Raw;

class ListExprTest extends QueryTestCase
{
    public function createDriver() {
        return new MySQLDriver;
    }

    public function testStringListExpr()
    {
        $expr = new ListExpr('1,2,3');
        $this->assertSql('(1,2,3)', $expr);
    }

    public function testRaw()
    {
        $expr = new ListExpr(new Raw('1,2,3'));
        $this->assertSql('(1,2,3)', $expr);
    }


    /**
     * @expectedException InvalidArgumentException
     */
    public function testUnknownType()
    {
        $expr = new ListExpr(1);
        $this->assertSql('(1,2,3)', $expr);
    }




}

