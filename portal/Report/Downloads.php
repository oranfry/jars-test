<?php

namespace OranFry\Jars\TestPortal\Report;

class Downloads extends \OranFry\Jars\Core\Report
{
    public function __construct()
    {
        $this->listen = ['download'];
    }
}
