<?php

namespace Magsql\PgSQL\Traits;

use Magsql\ArgumentArray;
use Magsql\Driver\BaseDriver;

trait ConcurrentlyTrait
{
    protected $concurrently;

    public function concurrently()
    {
        $this->concurrently = true;

        return $this;
    }

    public function buildConcurrentlyClause(BaseDriver $driver, ArgumentArray $args)
    {
        if ($this->concurrently) {
            return ' CONCURRENTLY';
        }

        return '';
    }
}
