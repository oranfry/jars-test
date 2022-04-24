<?php

namespace music\linetype;

class download extends \jars\Linetype
{
    function __construct()
    {
        parent::__construct();

        $this->table = 'download';

        $this->simple_strings('url');
    }
}
