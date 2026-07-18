<?php

namespace OranFry\Jars\TestPortal\Report;

class Artists extends \OranFry\Jars\Core\Report
{
    public function __construct()
    {
        $this->listen = ['artist'];
        $this->sorter = fn ($a, $b) => $a->name <=> $b->name;
    }
}
