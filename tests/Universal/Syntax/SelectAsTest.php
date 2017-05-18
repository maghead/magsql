<?php
use Magsql\Driver\MySQLDriver;
use Magsql\Driver\BaseDriver;
use Magsql\Bind;
use Magsql\ArgumentArray;
use Magsql\MySQL\Query\ExplainQuery;
use Magsql\Testing\QueryTestCase;
use Magsql\Testing\PDOQueryTestCase;
use Magsql\Universal\Query\SelectQuery;
use Magsql\Universal\Expr\FuncCallExpr;
use Magsql\Universal\Query\CreateTableQuery;
use Magsql\Universal\Query\DropTableQuery;
use Magsql\Universal\Syntax\SelectAs;
use Magsql\ANSI\AggregateFunction;

class SelectAsTest extends \PHPUnit\Framework\TestCase
{
    public function testString()
    {
        $expr = new SelectAs('products', 'p');
        $sql = $expr->toSql( new MySQLDriver, new ArgumentArray);
        ok($sql);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testUnknownTypeExpr()
    {
        $expr = new SelectAs(TRUE, 'p');
        $sql = $expr->toSql( new MySQLDriver, new ArgumentArray);
    }


    public function testFuncExpr() {
        $expr = new SelectAs(AggregateFunction::COUNT('*'), 'a');
        $sql = $expr->toSql( new MySQLDriver, new ArgumentArray);
        is('COUNT(*) AS `a`', $sql);
    }
}

