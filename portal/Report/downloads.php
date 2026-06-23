<?php

namespace OranFry\Jars\TestPortal\Report;

class downloads extends \OranFry\Jars\Core\Report
{
    public function __construct()
    {
        $this->listen = ['download'];
    }
}
