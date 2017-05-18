<?php
use Magsql\Driver\MySQLDriver;
use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;
use Magsql\MySQL\Query\CreateUserQuery;
use Magsql\MySQL\Query\DropUserQuery;

class DropUserQueryTest extends \PHPUnit\Framework\TestCase
{
    public function testDropSingleUser()
    {
        $driver = new MySQLDriver;
        $args = new ArgumentArray;
        $q = new DropUserQuery;
        $q->user()->account('monty')->host('localhost');
        $sql = $q->toSql($driver, $args);
        $this->assertEquals("DROP USER `monty`@`localhost`", $sql);
    }

    public function testDropSingleUserWithSpecString()
    {
        $driver = new MySQLDriver;
        $args = new ArgumentArray;
        $q = new DropUserQuery;
        $q->user('monty@localhost');
        $sql = $q->toSql($driver, $args);
        $this->assertEquals("DROP USER `monty`@`localhost`", $sql);
    }
}

