<?php

namespace musictest;

class Router extends \Router
{
    protected static $routes = [
        'CLI test' => ['PAGE', 'VERBOSE' => false, 'LAYOUT' => 'raw'],
        'CLI test -v' => ['PAGE', null, 'VERBOSE' => true, 'LAYOUT' => 'raw'],
    ];
}
