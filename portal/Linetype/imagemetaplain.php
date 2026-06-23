<?php

namespace OranFry\Jars\TestPortal\Linetype;

class imagemetaplain extends \OranFry\HasImages\Linetype\imagemetaplain
{
    use \OranFry\SimpleFields\Traits\SimpleFields;

    function __construct()
    {
        parent::__construct();

        $this->simple_int('external_id');
    }
}
