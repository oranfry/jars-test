<?php

namespace music\report;

class artists extends \Report
{
    public function __construct()
    {
        $this->listen = ['artist'];
        $this->sorter = object_string_comparator('name');
    }
}
