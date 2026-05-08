<?php

namespace music\report;

class imagemetas extends \OranFry\Jars\Core\Report
{
    public function __construct()
    {
        $this->listen = ['imagemetaplain'];
    }
}
