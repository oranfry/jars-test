<?php

global $ids, $token, $version;

$original_version = $version;

$lorde_long_real_name = 'Ella Marija Lani Yelich-O\'Connor';
$lorde_short_real_name = 'Ella O\'Connor';
$benee_real_name = 'Stella Rose Bennett';

$data = [
    (object) [
        'id' => $ids['artist']['Lorde'],
        'type' => 'artist',
        'name' => $lorde_long_real_name,
    ],
];

change("Change Lorde's name to her long real name [$lorde_long_real_name] with base version $version");

save_expect($data, function ($output, $original) use (&$ids, $lorde_long_real_name) {
    if (!is_array($output)) {
        throw new TestFailedException('Output expected to be an array');
    }

    logger('Got array, as expected');

    $expected = count($original);

    if (count($output) != $expected) {
        throw new TestFailedException('Got [' . count($output) . '] elements in output, expected [' . $expected . ']');
    }

    logger('Array had ['. $expected . '] elements, as expected');

    $line = reset($output);

    if ($line->name !== $lorde_long_real_name) {
        throw new TestFailedException('Unexpected name [' . @$line->name . ']');
    }

    logger("Name is [$lorde_long_real_name], as expected");
});

$version = $original_version;

change("Change Lorde's name to her short real name [$lorde_short_real_name] with base version $version");

try {
    save_expect([
        (object) [
            'id' => $ids['artist']['Lorde'],
            'type' => 'artist',
            'name' => $lorde_short_real_name,
        ],
    ]);
} catch (\jars\contract\ConcurrentModificationException $cme) {}

if (!isset($cme)) {
    throw new TestFailedException('Expected ConcurrentModificationException but did not get one');
}

logger('Caught a ConcurrentModificationException, as expected');

change("Change Benee's name to her real name [$benee_real_name] with base version $version");

save_expect([
    (object) [
        'id' => $ids['artist']['Benee'],
        'type' => 'artist',
        'name' => $benee_real_name,
    ],
]);

logger('No ConcurrentModificationException, as expected');

// Change back

$data = [
    (object) [
        'id' => $ids['artist']['Lorde'],
        'type' => 'artist',
        'name' => 'Lorde',
    ],
    (object) [
        'id' => $ids['artist']['Benee'],
        'type' => 'artist',
        'name' => 'Benee',
    ],
];

change("Change Lorde and Benee's names back to their stage names with base version $version");

save_expect($data, function ($output, $original) use (&$ids, $lorde_long_real_name) {
    if (!is_array($output)) {
        throw new TestFailedException('Output expected to be an array');
    }

    logger('Got array, as expected');

    $expected = count($original);

    if (count($output) != $expected) {
        throw new TestFailedException('Got [' . count($output) . '] elements in output, expected [' . $expected . ']');
    }

    logger('Array had ['. $expected . '] elements, as expected');

    $line = reset($output);

    if ($line->name !== 'Lorde') {
        throw new TestFailedException('Unexpected name [' . @$line->name . ']');
    }

    logger('Name is [Lorde], as expected');
});

return [
    'album_artists' => require TEST_HOME . '/asset/data/album-artists-2.php',
];
