<?php

namespace OranFry\Jars\TestPortal\Report;

class imagemetas extends \OranFry\Jars\Core\Report
{
    public function __construct()
    {
        $this->listen = ['imagemetaplain'];
    }
}
