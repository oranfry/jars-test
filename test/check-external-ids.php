<?php

use jars\Jars;
use obex\Obex;

$jars = Jars::of(PORTAL_HOME, DB_HOME);
$jars->login(USERNAME, PASSWORD, true);

$collection = $jars->group('collection', 'all');

foreach ($album_cover_external_ids as $album_title => $external_id) {
    if (!$album = @array_values(array_filter($collection, fn ($o) => $o->title == $album_title))[0]) {
        throw new TestFailedException('Album [' . $album_title . '] could not be retrieve from collection');
    }

    if ($album->cover_image_external_id !== $external_id) {
        throw new TestFailedException('Album cover_image_external_id was [' . $album->cover_image_external_id . '] instead of expected [' . $external_id . ']');
    }

    logger('Cover image external_id was [' . $external_id . '], as expected');
}

$imagemetas = $jars->group('imagemetas', 'all');

foreach ($album_cover_external_ids as $album_title => $external_id) {
    if (!$album = @array_values(array_filter($collection, fn ($o) => $o->title == $album_title))[0]) {
        throw new TestFailedException('Album [' . $album_title . '] could not be retrieve from collection');
    }

    if (!@$album->cover_image_id) {
        throw new CouldNotTestException('Album [' . $album_title . '] does not have a cover imagemeta');
    }

    if (!$imagemeta = Obex::find($imagemetas, 'image_id', 'is', $album->cover_image_id)) {
        throw new CouldNotTestException('Image [' . $album->cover_image_id . '] could not be retrieved from imagemetas');
    }

    if ($imagemeta->external_id !== $external_id) {
        throw new TestFailedException('Imagemeta external_id was [' . $imagemeta->external_id . '] instead of expected [' . $external_id . ']');
    }

    logger('Imagemeta external_id was [' . $external_id . '], as expected');
}
