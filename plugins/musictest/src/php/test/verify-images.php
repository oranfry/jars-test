<?php

$version = refresh_reports();
info('version: ' . $version);

$jars = ApiClient::php(null, true);
$jars->login(USERNAME, PASSWORD);

$collection = $jars->report('collection', 'all', $version);
$metas = $jars->report('imagemetas', 'all', $version);
$lookup = [];

foreach ($album_covers as $album_title) {
    $lookup[$album_title] = $album = find_object($collection, 'title', 'is', $album_title);
    $cover_data = $jars->record('image', $album->cover_image_id);

    if (!$cover_data) {
        throw new TestFailedException('Cover image could not be retreived for album [' . $album_title . ']');
    }

    if ($cover_data !== $original_covers[$album_title]) {
        throw new TestFailedException('Cover does not match expected for album [' . $album_title . ']');
    }

    logger('Cover image verified for album [' . $album_title . ']');
}

foreach ($album_covers as $album_title) {
    $album = $lookup[$album_title];
    $meta = find_object($metas, 'image_id', 'is', $album->cover_image_id);

    if (!$meta) {
        throw new TestFailedException('Cover image meta could not be retreived for album [' . $album_title . ']');
    }

    if ($meta->title !== $expected_meta_title = 'Album - Cover - ' . $album_title) {
        throw new TestFailedException('Cover meta title does not match expected for album [' . $album_title . ']: got [' . $meta->title . '], expected [' . $expected_meta_title . ']');
    }

    logger('Cover image meta title was [' . $expected_meta_title . '], as expected');
}
