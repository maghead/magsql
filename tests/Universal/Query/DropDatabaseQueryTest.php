<?php
use Magsql\Driver\MySQLDriver;
use Magsql\Driver\PgSQLDriver;
use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;
use Magsql\Universal\Query\DropDatabaseQuery;
use Magsql\ToSqlInterface;
use Magsql\Testing\QueryTestCase;

class DropDatabaseQueryTest extends QueryTestCase
{
    public function createDriver() {
        return new MySQLDriver;
    }

    public function testQuery() {
        $q = new DropDatabaseQuery('test');
        $this->assertSql("DROP DATABASE `test`", $q);
        $q->drop('test2');
        $this->assertSql("DROP DATABASE `test2`", $q);
    }

    public function testDropDatabaseQuery() {
        $q = new DropDatabaseQuery('test');
        $this->assertSqlStrings($q, [ 
            [ new PgSQLDriver, 'DROP DATABASE "test"'],
            [ new MySQLDriver, "DROP DATABASE `test`"],
        ]);
    }
}
