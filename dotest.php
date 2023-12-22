<?php

if (!defined('BIN_HOME')) {
    error_log(__FILE__ . ': Please define BIN_HOME before require me.');

    die(1);
}

const TEST_HOME = __DIR__;
const DB_HOME = '/var/tmp/jars-test-db';
const CONNECTION_STRING = 'local:music\\JarsConfig,' . DB_HOME;
const VERBOSE = true;
const USERNAME = 'music';
const PASSWORD = '123456';

require __DIR__ . '/portal/vendor/autoload.php';

require TEST_HOME . '/lib.php';

shell_exec('rm -rf "' . DB_HOME . '"; mkdir "' . DB_HOME . '"');

try {
    require __DIR__ . '/all-tests.php';
} catch(TestFailedException $e) {
    echo "\n\n\n\n";
    echo "-------------------------\n";
    echo "        TEST FAILED      \n";
    echo "-------------------------\n";
    echo "\n";
    echo $e->getMessage() . "\n";
    echo "\n";
    echo "That's all we know...\n";
    echo "\n\n\n";

    die(1);
}

echo "\n";
logger('ALL TESTS PASSED');
echo "\n";
