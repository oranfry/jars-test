<?php

global $ids;

$album_tracks = [
    'Color Theory' => ['bloodstream', 'circle the drain', 'royal screw up', 'night swimming', 'crawling in my skin', 'yellow is the color of her eyes', 'up the walls', 'lucy', 'stain', 'gray light'],
    'Immunity' => ['Alewife', 'Impossible', 'Closer to You', 'North', 'Bags', 'Softly', 'Sofia', 'White Flag', 'Feel Something', 'Sinking', 'I Wouldn\'t Ask You'],
    'dont smile at me' => ['Copycat', 'Idontwannabeyouanymore', 'My Boy', 'Watch', 'Party Favor', 'Bellyache', 'Ocean Eyes', 'Hostage'],
];

$track_data = [];

foreach ($album_tracks as $album => $tracks) {
    foreach ($tracks as $i => $track_title) {
        $track_data[] = (object) [
            'type' => 'track',
            'number' => $i + 1,
            'title' => $track_title,
            'album_id' => $ids['album'][$album],
        ];
    }
}

shuffle($track_data);

save_expect($track_data, function ($output) use (&$ids) {
    foreach ($output as $item) {
        $ids['track'][$item->title] = $item->id;
    }
});

$version = refresh_reports();
info('version: ' . $version);

$jars = ApiClient::php(null, true);
$jars->login(USERNAME, PASSWORD);

$collection = $jars->report('collection', 'all', $version);

foreach ($collection as $album) {
    if (array_key_exists($album->title, $album_tracks)) {
        $expected_tracks = $album_tracks[$album->title];

        foreach ($album->tracks as $track) {
            if (false === $pos = array_search($track->title, $expected_tracks)) {
                throw new TestFailedException('Unexpected track [' . $track->title . '] for album [' . $album->title . ']');
            }

            logger('Found expected track [' . $track->title . '] on album id [' . $album->title . ']');

            unset($expected_tracks[$pos]);
        }

        if (count($expected_tracks)) {
            throw new TestFailedException('Expected track(s) not found: [' . implode(', ', $expected_tracks) . '] for album [' . $album->title . ']');
        }
    }
}