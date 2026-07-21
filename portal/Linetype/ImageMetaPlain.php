<?php

namespace OranFry\Jars\TestPortal\Linetype;

class ImageMetaPlain extends \OranFry\HasImages\Linetypes\ImageMetaPlain
{
    use \OranFry\SimpleFields\Traits\SimpleFields;

    function __construct()
    {
        parent::__construct();

        $this->simple_int('external_id');
    }
}
