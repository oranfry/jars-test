<?php

namespace music\linetype;

class imagemeta extends \hasimages\linetype\imagemeta
{
    function __construct()
    {
        parent::__construct();

        $this->simple_ints('external_id');
    }
}
