<?php

use jars\Jars;

$jars = Jars::of(PORTAL_HOME, DB_HOME);
$jars->login(USERNAME, PASSWORD, true);

$collection = $jars->group('collection', 'all');

foreach ($collection as $album) {
    if (array_key_exists($album->title, $album_tracks)) {
        $expected_tracks = $album_tracks[$album->title];

        foreach ($album->tracks as $track) {
            if (false === $pos = array_search($track->title, $expected_tracks)) {
                throw new TestFailedException('Unexpected track [' . $track->title . '] for album [' . $album->title . ']');
            }

            logger('Found expected track [' . $track->title . '] on album id [' . $album->title . ']');

            unset($expected_tracks[$pos]);
        }

        if (count($expected_tracks)) {
            throw new TestFailedException('Expected track(s) not found: [' . implode(', ', $expected_tracks) . '] for album [' . $album->title . ']');
        }
    }
}
