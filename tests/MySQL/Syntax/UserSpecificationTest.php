<?php
use Magsql\Driver\MySQLDriver;
use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;
use Magsql\MySQL\Query\CreateUserQuery;
use Magsql\Testing\QueryTestCase;
use Magsql\MySQL\Syntax\UserSpecification;

class UserSpecificationTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateWithSpec()
    {
        $spec = UserSpecification::createWithFormat(NULL, 'localuser@localhost');
        $this->assertInstanceOf('Magsql\MySQL\Syntax\UserSpecification', $spec);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCreateWithWrongFormat()
    {
        UserSpecification::createWithFormat(NULL, 'localuser_localhost');
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testBadMethodCall()
    {
        $spec = new UserSpecification(NULL);
        $spec->foo();
    }
}

