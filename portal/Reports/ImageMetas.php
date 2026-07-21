<?php

namespace OranFry\Jars\TestPortal\Reports;

class ImageMetas extends \OranFry\Jars\Core\Report
{
    public function __construct()
    {
        $this->listen = ['imagemetaplain'];
    }
}
