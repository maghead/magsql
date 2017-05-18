<?php

namespace Magsql\Universal\Syntax;

use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;

class GroupConditions extends Conditions
{
    public $parent;

    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    public function endgroup()
    {
        return $this->parent;
    }

    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        return '('.parent::toSql($driver, $args).')';
    }
}
