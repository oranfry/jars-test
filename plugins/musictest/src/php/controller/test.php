<?php

if (!$db_home = @Config::get()->db_home) {
    error_response('db_home not set');
}

shell_exec('rm -rf ' . TEST_HOME . '/db/music; mkdir ' . TEST_HOME . '/db/music');

try {
    require __DIR__ . '/../script/all-tests.php';
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
}

return [];
