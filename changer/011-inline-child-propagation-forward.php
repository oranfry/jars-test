<?php

use obex\Obex;
use jars\Jars;

$jars = Jars::of(PORTAL_HOME, DB_HOME);
$jars->login(USERNAME, PASSWORD, true);

$collection_download_urls = require TEST_HOME . '/asset/data/download-urls-1.php';
$collection = $jars->group('collection', 'all');

$data = array_merge(...array_map(function ($album_title) use ($collection, $collection_download_urls) {
    $album = Obex::find($collection, 'title', 'is', $album_title);
    $album_download_urls = $collection_download_urls[$album_title];

    return array_map(function ($track_title) use ($album, $album_download_urls) {
        $track = Obex::find($album->tracks, 'title', 'is', $track_title);

        $track->download_url = $album_download_urls[$track_title];

        return $track;
    }, array_keys($album_download_urls));
}, array_keys($collection_download_urls)));

shuffle($data);

$expect_callback = function ($output, $original) {
    foreach ($output as $i => $track) {
        if (@$track->download_url !== $original[$i]->download_url) {
            throw new TestFailedException('Track [' . @$track->title . '] came back with unexpected download_url [' . @$track->download_url . '], expected [' . $original[$i]->download_url . ']');
        }

        logger('Track [' . @$track->title . '] came back with correct download_url [' . @$track->download_url . ']');
    }

    logger('Download URL correct');
};

info('preview');
preview_expect($data, $expect_callback);
info('save');
save_expect($data, $expect_callback);

return compact('collection_download_urls');
