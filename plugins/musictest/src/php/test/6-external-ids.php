<?php

global $ids;

$album_cover_external_ids = [
    'Clean' => 61,
    'Color Theory' => 32,
    'dont smile at me' => 13,
    'Hey U X' => 54,
    'Immunity' => 45,
    'Pure Heroine' => 76,
    'Solar Power' => 27,
];

$jars = ApiClient::php(null, true);
$jars->login(USERNAME, PASSWORD);

$collection = $jars->report('collection', 'all');
$data = [];

foreach ($album_cover_external_ids as $album_title => $external_id) {
    if (!$album = find_object($collection, 'title', 'is', $album_title)) {
        throw new CouldNotTestException('Album [' . $album_title . '] could not be retrieve from collection');
    }

    strip_non_scalars([$album]);

    $album->cover_image_external_id = $external_id;
    $data[] = $album;
}

shuffle($data);

save_expect($data);

$jars
    ->filesystem()
    ->persist()
    ->revert();

$version = refresh_reports();
info('version: ' . $version);

$collection = $jars->report('collection', 'all', $version);

foreach ($album_cover_external_ids as $album_title => $external_id) {
    $album = find_object($collection, 'title', 'is', $album_title);

    if (!$album = find_object($collection, 'title', 'is', $album_title)) {
        throw new TestFailedException('Album [' . $album_title . '] could not be retrieve from collection');
    }

    if ($album->cover_image_external_id !== $external_id) {
        throw new TestFailedException('Album cover_image_external_id was [' . $album->cover_image_external_id . '] instead of expected [' . $external_id . ']');
    }

    logger('Cover image external_id was [' . $external_id . '], as expected');
}
