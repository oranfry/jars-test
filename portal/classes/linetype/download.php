<?php

namespace music\linetype;

class download extends \jars\Linetype
{
    use \simplefields\traits\SimpleFields;

    function __construct()
    {
        parent::__construct();

        $this->table = 'download_r';

        $this->simple_string('url');
    }
}
