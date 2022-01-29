<?php

global $ids;

$album_covers = [
    'Clean',
    'Color Theory',
    'dont smile at me',
    'Hey U X',
    'Immunity',
    'Pure Heroine',
    'Solar Power',
    'When We All Fall Asleep, Where Do We Go?',
];

$original_covers = [];
$data = [];

foreach ($album_covers as $album_title) {
    $original_covers[$album_title] = $image_data = file_get_contents(APP_HOME . '/assets/album_covers/' . $album_title . '.jpg');

    $data[] = (object) [
        'id' => $ids['album'][$album_title],
        'type' => 'albumimageset',
        'cover' => base64_encode($image_data),
    ];
}

shuffle($data);
save_expect($data);

return compact('album_covers', 'original_covers');
