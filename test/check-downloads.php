<?php

use jars\Jars;
use obex\Obex;

$jars = Jars::of(PORTAL_HOME, DB_HOME);
$jars->login(USERNAME, PASSWORD, true);

$collection = $jars->group('collection');

foreach ($collection_download_urls as $album_title => $album_download_urls) {
    if (!$album = Obex::find($collection, 'title', 'is', $album_title)) {
        throw new CouldNotTestException('Could not locate album [' . $album_title . ']');
    }

    foreach ($album_download_urls as $track_title => $download_url) {
        if (!$track = Obex::find($album->tracks, 'title', 'is', $track_title)) {
            throw new CouldNotTestException('Could not locate track [' . $track_title . ']');
        }

        $expected_download_url = $album_download_urls[$track->title];

        if ($track->download_url !== $expected_download_url) {
            throw new TestFailedException('Incorrect download URL [' . $track->download_url . '], expected [' . $expected_download_url . ']');
        }

        logger('Correct download URL for [' . $album_title . ' > ' . $track->title . ']: [' . $expected_download_url . ']');
    }
}

unset($jars);
