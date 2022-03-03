<?php

use jars\Jars;

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
];

shuffle($data);
preview_expect($data);

return [
    'album_tracks' => require TEST_HOME . '/asset/data/album-tracks-1.php',
];
