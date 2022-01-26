<?php
namespace music;

class Router extends \Router
{
    protected static $routes = [
        'CLI *' => ['FORWARD' => 'blends\\CliRouter'],
    ];
}
