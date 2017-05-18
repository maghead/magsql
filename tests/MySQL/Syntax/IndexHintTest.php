<?php
use Magsql\Universal\Query\CreateTableQuery;
use Magsql\Universal\Query\DropTableQuery;
use Magsql\Universal\Query\AlterTableQuery;
use Magsql\Testing\PDOQueryTestCase;
use Magsql\Testing\QueryTestCase;
use Magsql\Driver\MySQLDriver;
use Magsql\Driver\PgSQLDriver;
use Magsql\Driver\SQLiteDriver;
use Magsql\ArgumentArray;
use Magsql\Universal\Syntax\Column;
use Magsql\MySQL\Syntax\IndexHint;

class IndexHintTest extends QueryTestCase
{
    public $driverType = 'MySQL';

    public function createDriver() { return new MySQLDriver; }

    /**
     * @expectedException Magsql\Exception\IncompleteSettingsException
     */
    public function testIndexHintIncompleteSettings()
    {
        $hint = new IndexHint(NULL);
        $this->assertSql('', $hint);
    }


    /**
     * @expectedException BadMethodCallException
     */
    public function testIndexHintBadMethodCallException()
    {
        $hint = new IndexHint(NULL);
        $hint->foo();
    }

}
