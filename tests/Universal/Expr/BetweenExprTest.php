<?php
use Magsql\Universal\Expr\BetweenExpr;

class BetweenExprTest extends \PHPUnit\Framework\TestCase
{
    public function testBetweenExprVarExport()
    {
        $expr = new BetweenExpr('age', 12, 20);
        $code = 'return ' . var_export($expr, true) . ';';
        $ret = eval($code); 
        $this->assertInstanceOf('Magsql\Universal\Expr\BetweenExpr', $ret);
    }
}

