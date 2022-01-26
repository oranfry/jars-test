<?php

save_expect('1-albums', function ($output) {
    global $album_ids;

    if (!is_array($output)) {
        throw new TestFailedException('Output expected to be an array');
    }

    $album_ids = [];

    $expected = [
        'Hey U X' => 'Benee',
        'Sling' => 'Clairo',
        'Solar Power' => 'Lorde',
        'dont smile at me' => 'Billie Eilish',
        'Color Theory' => 'Soccer Mommy',
        'Immunity' => 'Clairo',
        'When We All Fall Asleep, Where Do We Go?' => 'Billie Eilish',
    ];

    logger('Response was an array');

    foreach ($output as $item) {
        if (!@$item->id) {
            throw new TestFailedException('Album missing id');
        }

        if (!array_key_exists(@$item->title, $expected)) {
            throw new TestFailedException('Unexpected album found [' . @$item->title . ']');
        }

        $album_ids[$item->title] = $item->id;
        unset($expected[$item->title]);

        logger('Found album [' . $item->title . '] with id [' . $item->id . ']');
    }

    if (count($expected)) {
        throw new TestFailedException('Not all expected albums found, missing [' . implode(', ', $expected) . ']');
    }

    logger('All expected albums found');
});
