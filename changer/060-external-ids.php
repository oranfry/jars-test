<?php

use jars\Jars;

$album_cover_external_ids = require TEST_HOME . '/asset/data/album-cover-external-ids.php';

$jars = Jars::of(PORTAL_HOME, DB_HOME);
$jars->login(USERNAME, PASSWORD, true);

$collection = $jars->group('collection', 'all');
$data = [];

foreach ($album_cover_external_ids as $album_title => $external_id) {
    if (!$album = @array_values(array_filter($collection, fn ($o) => $o->title == $album_title))[0]) {
        throw new CouldNotTestException('Album [' . $album_title . '] could not be retrieved from collection');
    }

    strip_non_scalars([$album]);

    $album->cover_image_external_id = $external_id;
    $data[] = $album;
}

shuffle($data);

save_expect($data);

return compact('album_cover_external_ids');
