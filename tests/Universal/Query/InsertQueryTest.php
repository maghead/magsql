<?php
use Magsql\Raw;
use Magsql\Bind;
use Magsql\ToSqlInterface;
use Magsql\ArgumentArray;
use Magsql\Universal\Query\InsertQuery;
use Magsql\Testing\PDOQueryTestCase;
use Magsql\Driver\MySQLDriver;
use Magsql\Driver\PgSQLDriver;
use Magsql\Driver\SQLiteDriver;

class InsertQueryTest extends PDOQueryTestCase
{
    public function testInsertOptions()
    {
        $query = new InsertQuery;
        $query->option('LOW_PRIORITY');
        $query->insert([ 'name' => 'John', 'confirmed' => true ])->into('users');
        $this->assertSqlStrings($query, [ 
            [ new MySQLDriver, 'INSERT LOW_PRIORITY INTO users (name,confirmed) VALUES (\'John\',TRUE)' ],
        ]);
    }

    public function testCrossPlatformInsert()
    {
        $query = new InsertQuery;
        $query->insert([ 'name' => 'John', 'confirmed' => true ])->into('users');
        $query->returning(['id', 'name']);
        $this->assertSqlStrings($query, [ 
            [ new MySQLDriver, 'INSERT INTO users (name,confirmed) VALUES (\'John\',TRUE)' ],
            [ new PgSQLDriver, 'INSERT INTO users (name,confirmed) VALUES (\'John\',TRUE) RETURNING id,name' ],
            [ new SQLiteDriver, 'INSERT INTO users (name,confirmed) VALUES (\'John\',1)' ],
        ]);
    }

    public function testInsertBasic()
    {
        $driver = new MySQLDriver;
        $driver->setNamedParamMarker();

        $args = new ArgumentArray;
        $query = new InsertQuery;
        $query->option('LOW_PRIORITY', 'IGNORE');
        $query->insert([ 'name' => new Bind('name', 'John'), 'confirmed' => new Bind('confirmed',true) ])->into('users');
        $query->returning('id');
        $sql = $query->toSql($driver, $args);
        $this->assertEquals('INSERT LOW_PRIORITY IGNORE INTO users (name,confirmed) VALUES (:name,:confirmed)', $sql);
        $this->assertEquals('John', $args[':name'] ); 
        $this->assertEquals(true, $args[':confirmed'] ); 
    }


    public function testInsertWithQuestionMark() {

        $driver = new MySQLDriver;
        $driver->setQMarkParamMarker();

        $args = new ArgumentArray;
        $query = new InsertQuery;
        $query->option('LOW_PRIORITY', 'IGNORE');
        $query->insert([ 'name' => new Bind('name','John'), 'confirmed' => new Bind('confirmed',true) ])->into('users');
        $query->returning('id');
        $sql = $query->toSql($driver, $args);
        $this->assertEquals('INSERT LOW_PRIORITY IGNORE INTO users (name,confirmed) VALUES (?,?)', $sql);
    }


}

