<?php

use jars\Jars;
use obex\Obex;

$album_cover_external_ids = require TEST_HOME . '/asset/data/album-cover-external-ids-2.php';

$jars = Jars::of(PORTAL_HOME, DB_HOME);
$jars->login(USERNAME, PASSWORD, true);

$collection = $jars->group('collection');
$imagemetas = $jars->group('imagemetas');

$data = [];

foreach ($album_cover_external_ids as $album_title => $external_id) {
    if (!$album = Obex::find($collection, 'title', 'is', $album_title)) {
        throw new CouldNotTestException('Album [' . $album_title . '] could not be retrieved from collection');
    }

    if (!@$album->cover_image_id) {
        throw new CouldNotTestException('Album [' . $album_title . '] does not have a cover imagemeta');
    }

    if (!$imagemeta = Obex::find($imagemetas, 'image_id', 'is', $album->cover_image_id)) {
        throw new CouldNotTestException('Image [' . $album->cover_image_id . '] could not be retrieved from imagemetas');
    }

    $imagemeta->external_id = $external_id;

    $data[] = $imagemeta;

    change('Set external_id of imagemeta [' . $imagemeta->id . '] to [' . $external_id . ']');
}

shuffle($data);

save_expect($data);

return compact('album_cover_external_ids');
