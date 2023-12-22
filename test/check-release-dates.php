<?php

use obex\Obex;

$jars = fresh_jars();
$jars->login(USERNAME, PASSWORD, true);

$collection = $jars->group('collection');

foreach ($release_dates as $album_title => $released) {
    if (!$album = Obex::find($collection, 'title', 'is', $album_title)) {
        throw new CouldNotTestException('Could not locate album [' . $album_title . ']');
    }

    $expected_released = $release_dates[$album->title];

    if ($album->released !== $expected_released) {
        throw new TestFailedException('Incorrect album released [' . $album->released . '], expected [' . $expected_released . ']');
    }

    logger('Correct album release_date for [' . $album->title . ']: [' . $expected_released . ']');

    $expected_age = $ages[$album->title];

    if ($album->age != $expected_age) {
        throw new TestFailedException('Incorrect album age for album [' . $album->title . ']: [' . $album->age . '], expected [' . $expected_age . ']');
    }

    logger('Correct album age for [' . $album->title . ']: [' . $expected_age . ']');
}

logger('All album release dates correct');
