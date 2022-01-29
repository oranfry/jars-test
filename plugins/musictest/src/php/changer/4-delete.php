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
    'album_artists' => [
        'Clean' => 'Soccer Mommy',
        'Color Theory' => 'Soccer Mommy',
        'dont smile at me' => 'Billie Eilish',
        'Hey U X' => 'Benee',
        'Immunity' => 'Clairo',
        // 'Nomitori No Uta' => 'Erutan', // removed
        'Pure Heroine' => 'Lorde',
        'Sling' => 'Clairo',
        'Solar Power' => 'Lorde',
        'The Court of Leaves' => 'Erutan',
        'When We All Fall Asleep, Where Do We Go?' => 'Billie Eilish',
    ],
];
