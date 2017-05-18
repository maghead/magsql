<?php
use Magsql\Driver\MySQLDriver;
use Magsql\Universal\Syntax\Paging;
use Magsql\ArgumentArray;

class PagingTest extends \PHPUnit\Framework\TestCase
{
    public function testLimit()
    {
        $driver = new MySQLDriver;
        $args = new ArgumentArray;
        $paging = new Paging;
        $paging->limit(10);
        $sql = $paging->toSql($driver, $args);
        is(' LIMIT 10', $sql);
        is(10, $paging->getLimit());
    }

    public function testOffset()
    {
        $driver = new MySQLDriver;
        $args = new ArgumentArray;
        $paging = new Paging;
        $paging->limit(10);
        $paging->offset(20);
        $sql = $paging->toSql($driver, $args);
        is(' LIMIT 10 OFFSET 20', $sql);
        is(20, $paging->getOffset());
    }

    public function testPageMethod()
    {
        $driver = new MySQLDriver;
        $args = new ArgumentArray;
        $paging = new Paging;
        $paging->page(2, 20);
        $sql = $paging->toSql($driver, $args);
        is(' LIMIT 20 OFFSET 20', $sql);
    }

}

