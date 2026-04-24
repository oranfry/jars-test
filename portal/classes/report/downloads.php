<?php

namespace music\report;

class downloads extends \OranFry\Jars\Core\Report
{
    public function __construct()
    {
        $this->listen = ['download'];
    }
}
