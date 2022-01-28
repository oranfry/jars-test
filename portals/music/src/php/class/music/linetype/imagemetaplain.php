<?php

namespace music\linetype;

class imagemetaplain extends \hasimages\linetype\imagemetaplain
{
    function __construct()
    {
        parent::__construct();

        $this->simple_ints('external_id');
    }
}
