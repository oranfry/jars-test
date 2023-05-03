<?php

namespace music\linetype;

class imagemeta extends \hasimages\linetype\imagemeta
{
    use \simplefields\traits\SimpleFields;

    function __construct()
    {
        parent::__construct();

        $this->simple_int('external_id');
    }
}
