<?php
use Magsql\Driver\MySQLDriver;
use Magsql\Driver\PgSQLDriver;
use Magsql\Universal\Syntax\Conditions;
use Magsql\Universal\Expr\IsExpr;
use Magsql\Criteria;
use Magsql\ArgumentArray;
use Magsql\DataType\Unknown;
use Magsql\Bind;
use Magsql\Raw;
use Magsql\Testing\QueryTestCase;

class IsExprTest extends QueryTestCase
{

    public function testConstructor() {
        $expr = new IsExpr('a', true);
        $this->assertSqlStrings($expr,[
            [new MySQLDriver,'a IS TRUE'],
        ]);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructorInvalidType() {
        $expr = new IsExpr('a', 'blah');
        $this->assertSqlStrings($expr,[
            [new MySQLDriver,'a IS TRUE'],
        ]);
    }

}
