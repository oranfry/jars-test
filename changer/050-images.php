<?php

global $ids;

$album_covers = require TEST_HOME . '/asset/data/album-covers.php';

$original_covers = [];
$data = [];

foreach ($album_covers as $album_title) {
    $original_covers[$album_title] = $image_data = file_get_contents(TEST_HOME . '/asset/album_covers/' . $album_title . '.jpg');

    $data[] = (object) [
        'id' => $ids['album'][$album_title],
        'type' => 'albumimageset',
        'cover' => base64_encode($image_data),
    ];

    change('Added cover image for album [' . $album_title . ']');
}

shuffle($data);
save_expect($data);

return compact('album_covers', 'original_covers');
