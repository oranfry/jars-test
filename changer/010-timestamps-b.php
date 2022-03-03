<?php

use obex\Obex;
use jars\Jars;

$jars = Jars::of(PORTAL_HOME, DB_HOME);
$jars->login(USERNAME, PASSWORD, true);

$release_dates = require TEST_HOME . '/asset/data/release-dates.php';
$collection = $jars->group('collection', 'all');

$data = array_map(function ($album_title) use ($collection, $release_dates) {
    $album = Obex::find($collection, 'title', 'is', $album_title);
    $album->timestamp = strtotime('2022-03-03');

    return $album;
}, array_keys($release_dates));

save_expect($data);

$ages = [
    'Clean' => 4,
    'Color Theory' => 2,
];

return compact('release_dates', 'ages');
