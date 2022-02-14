<?php

namespace music\report;

class artists extends \jars\Report
{
    public function __construct()
    {
        $this->listen = ['artist'];
        $this->sorter = fn ($a, $b) => $a->name <=> $b->name;
    }
}
