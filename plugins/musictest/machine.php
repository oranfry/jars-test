<?php

const USERNAME = 'music';
const PASSWORD = '983dk32dfmfa1s';

define('TEST_HOME', dirname(dirname(__DIR__)));
define('SAVE_CMD', TEST_HOME . '/portals/music/cli.php -u ' . USERNAME . ' -p ' . PASSWORD . ' save');

require __DIR__ . '/../../machine.php';
