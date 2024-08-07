<?php

$jars = fresh_jars();
$jars->login(USERNAME, PASSWORD, true);

$collection = $jars->group('collection');

$metas = $jars->group('imagemetas');
$lookup = [];

foreach ($album_covers as $album_title) {
    $lookup[$album_title] = $album = @array_values(array_filter($collection, fn ($o) => $o->title == $album_title))[0];
    $cover_data = $jars->record('image', $album->cover_image_id);

    if (!$cover_data) {
        throw new TestFailedException('Cover image could not be retreived for album [' . $album_title . '], tried with id [' . $album->cover_image_id . ']');
    }

    if ($cover_data !== $original_covers[$album_title]) {
        throw new TestFailedException('Cover does not match expected for album [' . $album_title . ']');
    }

    logger('Cover image verified for album [' . $album_title . ']');
}

foreach ($album_covers as $album_title) {
    $album = $lookup[$album_title];
    $meta = @array_values(array_filter($metas, fn ($o) => $o->image_id == $album->cover_image_id))[0];

    if (!$meta) {
        throw new TestFailedException('Cover image meta could not be retreived for album [' . $album_title . ']');
    }

    if ($meta->title !== $expected_meta_title = 'Album_r - Cover - ' . $album_title) {
        throw new TestFailedException('Cover meta title does not match expected for album [' . $album_title . ']: got [' . $meta->title . '], expected [' . $expected_meta_title . ']');
    }

    logger('Cover image meta title was [' . $expected_meta_title . '], as expected');
}

unset($jars);
