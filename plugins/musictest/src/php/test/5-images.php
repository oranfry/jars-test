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

$version = refresh_reports();
info('version: ' . $version);

$jars = ApiClient::php(null, true);
$jars->login(USERNAME, PASSWORD);

$collection = $jars->report('collection', 'all', $version);

foreach ($album_covers as $album_title) {
    $found = find_object($collection, 'title', 'is', $album_title);
    $cover_data = $jars->record('image', $found->cover_image_id);

    if (!$cover_data) {
        throw new TestFailedException('Cover image could not be retreived for album [' . $album_title . ']');
    }

    if ($cover_data !== $original_covers[$album_title]) {
        throw new TestFailedException('Cover does not match expected for album [' . $album_title . ']');
    }

    logger('Cover image verified for album [' . $album_title . ']');
}
