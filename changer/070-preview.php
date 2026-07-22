<?php

global $ids;

$disappearance_track_titles = require TEST_HOME . '/asset/data/disappearance-tracks.php';
$hidden_heyux_track = require TEST_HOME . '/asset/data/hidden-heyux-track.php';
$disappearance_tracks = [];

foreach ($disappearance_track_titles as $i => $track_title) {
    $disappearance_tracks[] = (object) [
        'number' => $i + 1,
        'title' => $track_title,
        'type' => 'track',
    ];
}

$data = [
    $hidden_heyux_track,
    (object) [
        'type' => 'album',
        'title' => 'The Disappearance of the Girl',
        'tracks' => $disappearance_tracks,
    ],
    (object) [
        'type' => 'artist',
        'name' => 'Lorde A',
        'id' => $ids['artist']['Lorde'],
    ],
];

shuffle($data);

preview_expect($data, function (array $output, array $original, $jars) use ($ids) {
    foreach ($output as $line) {
        if ($line->type === 'artist' && $line->id === $ids['artist']['Lorde']) {
            $line->name = 'Lorde B';
            $result = $jars->save([$line]);
            $got = reset($result)->name;

            if ($line->name !== $got) {
                throw new TestFailedException("Expected artist name [$line->name], got [$got]");
            }

            logger("Artist name was [$line->name], as expected");

            break;
        }
    }
});

return ['album_tracks' => require TEST_HOME . '/asset/data/album-tracks-1.php'];
