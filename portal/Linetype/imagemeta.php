<?php

namespace OranFry\Jars\TestPortal\Linetype;

class imagemeta extends \OranFry\HasImages\Linetype\imagemeta
{
    use \simplefields\traits\SimpleFields;

    function __construct()
    {
        parent::__construct();

        $this->simple_int('external_id');
    }
}
