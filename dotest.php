<?php

if (!defined('BIN_HOME')) {
    error_log(__FILE__ . ': Please define BIN_HOME before require me.');

    die(1);
}

if (!defined('JARS_TEST_VERBOSE')) {
    define('JARS_TEST_VERBOSE', false);
}

if (!defined('JARS_TEST_OUTPUT_THRESHOLD'))  {
    define('JARS_TEST_OUTPUT_THRESHOLD', 4096);
}

const TEST_HOME = __DIR__;
const DB_HOME = '/var/tmp/jars-test-db';
const CONNECTION_STRING = 'local:music\\JarsConfig,' . DB_HOME;
const USERNAME = 'music';
const PASSWORD = '123456';

require __DIR__ . '/portal/vendor/autoload.php';

require TEST_HOME . '/lib.php';

shell_exec(implode('; ', [
    'rm -rf "' . DB_HOME . '"',
    'mkdir "' . DB_HOME . '"',
    'mkdir "' . DB_HOME . '/master"',
    'mkdir "' . DB_HOME . '/chain"',
    'mkdir "' . DB_HOME . '/index"',
    'mkdir "' . DB_HOME . '/reports"',
]));

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

logger('ALL TESTS PASSED');
