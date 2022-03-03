<?php

use obex\Obex;
use jars\Jars;

$jars = Jars::of(PORTAL_HOME, DB_HOME);
$jars->login(USERNAME, PASSWORD, true);

$release_dates = require TEST_HOME . '/asset/data/release-dates.php';
$collection = $jars->group('collection', 'all');

$data = array_map(function ($album_title) use ($collection, $release_dates) {
    $album = Obex::find($collection, 'title', 'is', $album_title);
    $album->released = $release_dates[$album_title];
    $album->timestamp = strtotime('2022-03-02');

    return $album;
}, array_keys($release_dates));

save_expect($data, function ($output, $original) use ($release_dates) {
    var_dump($output); die();

    foreach ($output as $album) {
        if (!@$album->title) {
            throw new TestFailedException('Album came back with no title');
        }

        if (!($expected_released = @$release_dates[$album->title])) {
            throw new TestFailedException('Unexpected album title came back');
        }

        if ($album->released !== $expected_released) {
            throw new TestFailedException('Incorrect album release date for album [' . $album->title . ']: [' . $album->released . '], expected [' . $expected_released . ']');
        }
    }
});

$ages = [
    'Clean' => 3,
    'Color Theory' => 2,
];

return compact('release_dates', 'ages');
