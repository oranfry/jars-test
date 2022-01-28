<?php

$db_home = dirname(dirname(__DIR__)) . '/db/music';

return (object) [
    'db_home' => $db_home,
    'entrypoints' => [
        'base' => 'music\config\base',
        'root' => 'music\config\base',
    ],
    'requires' => [
        WWW_HOME . '/plugins/hasimages',
    ],
    'router' => 'music\Router',
];
