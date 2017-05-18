<?php

class ReferenceOptionTest extends \PHPUnit\Framework\TestCase
{
    public function test()
    {
        $this->assertEquals('RESTRICT', Magsql\MySQL\Constant\ReferenceOption::RESTRICT );
    }
}

