<?php

global $ids;

$album_tracks = require TEST_HOME . '/asset/data/album-tracks-1.php';

$track_data = [];

foreach ($album_tracks as $album => $tracks) {
    foreach ($tracks as $i => $track_title) {
        $track_data[] = (object) [
            'album_id' => $ids['album'][$album],
            'number' => $i + 1,
            'title' => $track_title,
            'type' => 'track',
        ];

        change('Added track [' . $track_title . '] to album [' . $album . ']');
    }
}

shuffle($track_data);

save_expect($track_data, function ($output) use (&$ids) {
    foreach ($output as $item) {
        $ids['track'][$item->title] = $item->id;
    }
});

return compact('album_tracks');
