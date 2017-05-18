<?php

namespace Magsql\MySQL\Query;

use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;
use Magsql\MySQL\Traits\UserSpecTrait;

/**
 * @see http://dev.mysql.com/doc/refman/5.5/en/drop-user.html
 */
class DropUserQuery
{
    use UserSpecTrait;

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        $specSql = array();
        foreach ($this->userSpecifications as $spec) {
            $specSql[] = $spec->getIdentitySql($driver, $args);
        }

        return 'DROP USER '.implode(', ', $specSql);
    }
}
