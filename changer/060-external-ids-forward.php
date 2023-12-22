<?php

use obex\Obex;

$album_cover_external_ids = require TEST_HOME . '/asset/data/album-cover-external-ids-1.php';

$jars = fresh_jars();
$jars->login(USERNAME, PASSWORD, true);

$collection = $jars->group('collection');
$data = [];

foreach ($album_cover_external_ids as $album_title => $external_id) {
    if (!$album = Obex::find($collection, 'title', 'is', $album_title)) {
        throw new CouldNotTestException('Album [' . $album_title . '] could not be retrieved from collection');
    }

    strip_non_scalars([$album]);

    $album->cover_image_external_id = $external_id;

    $data[] = $album;

    change('Added external_id to album [' . $album_title . ']');
}

shuffle($data);

save_expect($data);

return compact('album_cover_external_ids');
