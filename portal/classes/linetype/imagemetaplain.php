<?php

namespace music\linetype;

class imagemetaplain extends \hasimages\linetype\imagemetaplain
{
    function __construct()
    {
        parent::__construct();

        $this->simple_int('external_id');
    }
}
