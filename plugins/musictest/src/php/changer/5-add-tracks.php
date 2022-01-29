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

return compact('album_tracks');
