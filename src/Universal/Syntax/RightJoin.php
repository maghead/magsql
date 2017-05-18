<?php

namespace Magsql\Universal\Syntax;

use Magsql\ToSqlInterface;

class RightJoin extends Join implements ToSqlInterface
{
    protected $joinType = 'RIGHT';
}
