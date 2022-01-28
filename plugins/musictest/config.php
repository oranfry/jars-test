<?php

return (object) [
    'db_home' => TEST_HOME . '/db/music',
    'entrypoints' => [
        'base' => 'music\config\base',
        'root' => 'music\config\base',
    ],
    'requires' => [
        TEST_HOME . '/portals/music',
        WWW_HOME . '/plugins/hasimages',
        WWW_HOME . '/plugins/subsimple',
        WWW_HOME . '/plugins/apiclient',
        WWW_HOME . '/plugins/blends',
    ],
    'router' => 'musictest\Router',
];
