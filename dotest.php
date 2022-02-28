<?php

if (!defined('BIN_HOME')) {
    error_log(__FILE__ . ': Please define BIN_HOME before require me.');

    die(1);
}

const TEST_HOME = __DIR__;
const PORTAL_HOME = TEST_HOME . '/portal';
const DB_HOME = '/tmp/jars-test-db';
const VERBOSE = true;
const USERNAME = 'music';
const PASSWORD = '983dk32dfmfa1s';

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
