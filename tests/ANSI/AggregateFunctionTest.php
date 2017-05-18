<?php
use Magsql\ArgumentArray;
use Magsql\Bind;
use Magsql\Driver\MySQLDriver;
use Magsql\Universal\Query\SelectQuery;
use Magsql\ANSI\AggregateFunction;
use Magsql\Universal\Syntax\SelectAs;
use Magsql\Universal\Syntax\Distinct;

class AggregateFunctionTest extends \PHPUnit\Framework\TestCase
{

    public function getFunctionTests() {
        $args = array();
        $args[] = [ AggregateFunction::SUM(10) , 'SELECT SUM(10)' ];
        $args[] = [ AggregateFunction::SUM('total_amount') , 'SELECT SUM(total_amount)' ];
        $args[] = [ AggregateFunction::SUM(new Distinct('total_amount')) , 'SELECT SUM(DISTINCT total_amount)' ];
        $args[] = [ AggregateFunction::MAX('views') , 'SELECT MAX(views)' ];
        $args[] = [ AggregateFunction::MIN('views') , 'SELECT MIN(views)' ];
        $args[] = [ AggregateFunction::AVG('buyPrice') , 'SELECT AVG(buyPrice)' ];
        $args[] = [ AggregateFunction::COUNT('*') , 'SELECT COUNT(*)' ];
        $args[] = [ new SelectAs(AggregateFunction::COUNT('*'), 'a'), 'SELECT COUNT(*) AS `a`'];
        return $args;
    }

    public function testFunctions()
    {
        $driver = new MySQLDriver;

        $tests = $this->getFunctionTests();
        foreach($tests as $test) {
            $args = new ArgumentArray;
            list($func, $expectedSql) = $test;
            $query = new SelectQuery;
            $query->select($func);
            $sql = $query->toSql($driver, $args);
            $this->assertEquals($expectedSql,$sql);
        }

    }

}

