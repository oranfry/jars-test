<?php

namespace music\linetype;

class download extends \jars\Linetype
{
    use \simplefields\traits\SimpleFields;

    function __construct()
    {
        parent::__construct();

        $this->table = 'download';

        $this->simple_string('url');
    }
}
