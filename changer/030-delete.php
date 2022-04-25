<?php

global $ids;

$data = [
    (object) [
        'type' => 'album',
        'id' => $ids['album']['Nomitori No Uta'],
        '_is' => false,
    ],
];

shuffle($data);

foreach ($data as $line) {
    change('Deleted album [' . $line->id . ']');
}

save_expect($data, function ($output, $original) use (&$ids) {
    if (!is_array($output)) {
        throw new TestFailedException('Output expected to be an array');
    }

    logger('Got array, as expected');

    $expected = count($original);

    if (count($output) != $expected) {
        throw new TestFailedException('Got [' . count($output) . '] elements in output, expected [' . $expected . ']');
    }

    logger('Array had ['. $expected . '] elements, as expected');
});

return [
    'album_artists' => require TEST_HOME . '/asset/data/album-artists-3.php',
];
