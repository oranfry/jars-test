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
    'album_artists' => [
        'Clean' => 'Soccer Mommy', // added
        'Color Theory' => 'Soccer Mommy',
        'dont smile at me' => 'Billie Eilish',
        'Hey U X' => 'Benee',
        'Immunity' => 'Clairo',
        'Nomitori No Uta' => 'Erutan', // added
        'Pure Heroine' => 'Lorde', // added
        'Sling' => 'Clairo',
        'Solar Power' => 'Lorde',
        'The Court of Leaves' => 'Erutan', // added
        'When We All Fall Asleep, Where Do We Go?' => 'Billie Eilish',
    ],
];
