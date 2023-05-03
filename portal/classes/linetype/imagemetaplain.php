<?php

namespace music\linetype;

class imagemetaplain extends \hasimages\linetype\imagemetaplain
{
    use \simplefields\traits\SimpleFields;

    function __construct()
    {
        parent::__construct();

        $this->simple_int('external_id');
    }
}
