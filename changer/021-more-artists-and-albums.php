<?php

global $ids;

$data = [
    (object) [
        'type' => 'album',
        'title' => 'Clean',
        'artist_id' => $ids['artist']['Soccer Mommy'],
    ],
    (object) [
        'type' => 'artist',
        'name' => 'Erutan',
        'albums' => [
            (object) ['title' => 'Nomitori No Uta'],
            (object) ['title' => 'The Court of Leaves'],
        ],
    ],
    (object) [
        'type' => 'artist',
        'name' => 'Lorde',
        'id' => $ids['artist']['Lorde'],
        'albums' => [
            (object) ['title' => 'Pure Heroine'],
        ],
    ],
];

shuffle($data);

foreach ($data as $line) {
    switch ($line->type) {
        case 'artist':
            change('Added artist [' . $line->name . ']');

            foreach ($line->albums ?? [] as $album) {
                change('Added nested album [' . $album->title . ']');
            }

            break;

        case 'album':
            change('Added album [' . $line->title . ']');

            break;
    }
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

    foreach ($output as $item) {
        switch ($item->type) {
            case 'album':
                $ids['album'][$item->title] = $item->id;
                break;

            case 'artist':
                $ids['artist'][$item->name] = $item->id;

                foreach (@$item->albums ?: [] as $album) {
                    $ids['album'][$album->title] = $album->id;
                }

                break;

            default:
                throw new TestFailedException('Unexpected item type [' . @$item->type . ']');
        }
    }
});

return [
    'album_artists' => require TEST_HOME . '/asset/data/album-artists-2.php',
];
