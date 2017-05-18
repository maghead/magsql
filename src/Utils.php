<?php

namespace Magsql;

class Utils
{
    public static function buildExprArg($a)
    {
        if ($a instanceof ToSqlInterface) {
            return $a;
        } else {
            return new Raw($a);
        }
    }

    public static function buildFunctionArguments($args)
    {
        return array_map(array('Magsql\\Utils', 'buildExprArg'), $args);
    }
}
