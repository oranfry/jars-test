<?php

namespace OranFry\Jars\TestPortal\Linetype;

class ImageMetaPlain extends \OranFry\HasImages\Linetype\imagemetaplain
{
    use \OranFry\SimpleFields\Traits\SimpleFields;

    function __construct()
    {
        parent::__construct();

        $this->simple_int('external_id');
    }
}
