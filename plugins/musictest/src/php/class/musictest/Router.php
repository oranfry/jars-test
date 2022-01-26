<?php

namespace musictest;

class Router extends \Router
{
    protected static $routes = [
        'CLI test' => ['PAGE', 'LAYOUT' => 'raw'],
    ];
}
