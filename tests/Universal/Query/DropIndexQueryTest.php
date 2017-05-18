<?php
use Magsql\Driver\MySQLDriver;
use Magsql\Driver\PgSQLDriver;
use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;
use Magsql\Universal\Query\DropIndexQuery;
use Magsql\Testing\QueryTestCase;


class DropIndexQueryTest extends QueryTestCase
{

    public function createDriver() {
        return new MySQLDriver; 
    }

    public function testDropIndexSimple()
    {
        $q = new DropIndexQuery;
        $q->drop('idx_book')->on('books');
        $this->assertSqlStrings($q, [
            [ new MySQLDriver , "DROP INDEX `idx_book` ON `books`"],
            [ new PgSQLDriver , 'DROP INDEX "idx_book"'],
        ]);
    }


    /**
     * @expectedException Magsql\Exception\IncompleteSettingsException
     */
    public function testDropIndexWithoutTable()
    {
        $q = new DropIndexQuery;
        $q->drop('idx_book');
        $this->assertSqlStrings($q, [
            [ new MySQLDriver , "DROP INDEX `idx_book` IF EXISTS ON `books` LOCK = DEFAULT ALGORITHM = DEFAULT"],
            [ new PgSQLDriver , 'DROP INDEX "idx_book" IF EXISTS CASCADE'],
        ]);
    }


    public function testDropIndexCascade()
    {
        $q = new DropIndexQuery;
        $q->drop('idx_book')->on('books')->ifExists();
        $q->lock('DEFAULT');
        $q->algorithm('DEFAULT');
        $q->cascade();

        $this->assertSqlStrings($q, [
            [ new MySQLDriver , "DROP INDEX `idx_book` IF EXISTS ON `books` LOCK = DEFAULT ALGORITHM = DEFAULT"],
            [ new PgSQLDriver , 'DROP INDEX "idx_book" IF EXISTS CASCADE'],
        ]);
    }

    public function testDropIndexRestrict()
    {
        $q = new DropIndexQuery;
        $q->drop('idx_book')->on('books')->ifExists();
        $q->restrict();
        $this->assertSqlStrings($q, [
            [ new MySQLDriver , "DROP INDEX `idx_book` IF EXISTS ON `books`"],
            [ new PgSQLDriver , 'DROP INDEX "idx_book" IF EXISTS RESTRICT'],
        ]);
    }
}

