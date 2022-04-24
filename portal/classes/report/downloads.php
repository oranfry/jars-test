<?php

namespace music\report;

class downloads extends \jars\Report
{
    public function __construct()
    {
        $this->listen = ['download'];
    }
}
