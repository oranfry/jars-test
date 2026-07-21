<?php

namespace OranFry\Jars\TestPortal\Reports;

class Downloads extends \OranFry\Jars\Core\Report
{
    public function __construct()
    {
        $this->listen = ['download'];
    }
}
