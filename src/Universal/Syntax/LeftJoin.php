<?php

namespace Magsql\Universal\Syntax;

use Magsql\ToSqlInterface;

class LeftJoin extends Join implements ToSqlInterface
{
    protected $joinType = 'LEFT';
}
