<?php

$db_home = dirname(dirname(__DIR__)) . '/db/music';

return (object) [
    'db_home' => $db_home,
    'entrypoints' => [
        'base' => 'music\config\base',
        'root' => 'music\config\base',
    ],
    'report_fields' => [
        'artists' => ['name'],
        'collection' => ['title', 'artist_name'],
    ],
    'requires' => [
        WWW_HOME . '/plugins/hasimages',
    ],
    'respect_newline_fields' => [],
    'router' => 'music\Router',
];
