<?php

save_expect('1-artists', function ($output) {
    global $artist_ids;

    if (!is_array($output)) {
        throw new TestFailedException('Output expected to be an array');
    }

    $artist_ids = [];

    $expected = [
        'Benee',
        'Billie Eilish',
        'Clairo',
        'Lorde',
        'Soccer Mommy',
    ];

    logger('Response was an array');

    foreach ($output as $item) {
        if (!@$item->id) {
            throw new TestFailedException('Artist missing id');
        }

        if (false === $pos = array_search(@$item->name, $expected)) {
            throw new TestFailedException('Unexpected artist found [' . @$item->name . ']');
        }

        $artist_ids[$item->name] = $item->id;
        unset($expected[$pos]);

        logger('Found artist [' . $item->name . '] with id [' . $item->id . ']');
    }

    if (count($expected)) {
        throw new TestFailedException('Not all expected artists found');
    }

    logger('All expected artists found');
});
