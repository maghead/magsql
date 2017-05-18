<?php

namespace Magsql\MySQL\Traits;

use Magsql\Driver\BaseDriver;
use Magsql\ArgumentArray;
use Magsql\MySQL\Syntax\Partition;

trait PartitionTrait
{
    protected $partitions;

    public function partitions($partitions)
    {
        if (is_array($partitions)) {
            $this->partitions = new Partition($partitions);
        } else {
            $this->partitions = new Partition(func_get_args());
        }

        return $this;
    }

    public function buildPartitionClause(BaseDriver $driver, ArgumentArray $args)
    {
        if ($this->partitions) {
            return $this->partitions->toSql($driver, $args);
        }

        return '';
    }
}
