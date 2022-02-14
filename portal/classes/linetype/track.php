<?php

namespace music\linetype;

class track extends \jars\Linetype
{
    function __construct()
    {
        parent::__construct();

        $this->table = 'track';

        $this->simple_ints('number');
        $this->simple_strings('title');
    }
}
