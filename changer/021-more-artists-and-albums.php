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

save_expect_albums_artists($data, $ids);

return [
    'album_artists' => require TEST_HOME . '/asset/data/album-artists-2.php',
];
