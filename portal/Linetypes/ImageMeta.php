<?php

namespace OranFry\Jars\TestPortal\Linetypes;

class ImageMeta extends \OranFry\HasImages\Linetypes\ImageMeta
{
    use \OranFry\SimpleFields\Traits\SimpleFields;

    function __construct()
    {
        parent::__construct();

        $this->simple_int('external_id');
    }
}
