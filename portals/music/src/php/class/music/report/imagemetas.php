<?php

namespace music\report;

class imagemetas extends \Report
{
    public function __construct()
    {
        $this->listen = ['imagemetaplain'];
    }
}
