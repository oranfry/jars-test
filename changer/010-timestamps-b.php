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

$ages = [
    'Clean' => 4,
    'Color Theory' => 2,
];

$expect_callback = function ($output, $original) use ($ages) {
    foreach ($output as $album) {
        if (@$ages[@$album->title] !== $album->age) {
            throw new TestFailedException('Album [' . $album->title . '] came back with unexpected age [' . $album->age . '], expected [' . @$ages[@$album->title] . ']');
        }

        logger('Album [' . $album->title . '] came back with expected age: [' . $ages[$album->title] . ']');
    }
};

info('preview');
preview_expect($data, $expect_callback);
info('save');
save_expect($data, $expect_callback);

return compact('release_dates', 'ages');
