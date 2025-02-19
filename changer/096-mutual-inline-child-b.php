<?php

use obex\Obex;

$jars = fresh_jars();
$jars->login(USERNAME, PASSWORD, true);

// Now delete the same URL(s) via the inline child

$collection_download_urls = require TEST_HOME . '/asset/data/download-urls-3.php';
$collection = $jars->group('collection');
$downloads = $jars->group('downloads');

$data = [];

foreach ($collection_download_urls as $album_title => $album_download_urls) {
    $album = Obex::find($collection, 'title', 'is', $album_title);

    foreach ($album_download_urls as $track_title => $download_url) {
        $track = Obex::find($album->tracks, 'title', 'is', $track_title);
        $download = Obex::find($downloads, 'id', 'is', $track->download_id);

        $download->_is = false;
        $collection_download_urls[$album_title][$track_title] = null;

        $data[] = $download;
    }
}

$data = array_merge(...array_map(function ($album_title) use ($collection, $collection_download_urls, $downloads) {
    $album = Obex::find($collection, 'title', 'is', $album_title);
    $album_download_urls = $collection_download_urls[$album_title];

    return array_map(function ($track_title) use ($album, $album_download_urls, $downloads) {
        $track = Obex::find($album->tracks, 'title', 'is', $track_title);
        $download = Obex::find($downloads, 'id', 'is', $track->download_id);
        $download->url = $album_download_urls[$track_title];

        return $download;
    }, array_keys($album_download_urls));
}, array_keys($collection_download_urls)));

shuffle($data);

$expect_callback = function ($output, $original) {
    foreach ($output as $i => $download) {
        if (@$download->_is !== false) {
            throw new TestFailedException('Download [' . @$download->id . '] came back without _is being the expected value of false');
        }

        logger('Download [' . @$download->id . '] came back with _is false');
    }

    logger('Download existence correct');
};

info('preview');
preview_expect($data, $expect_callback);
info('save');
save_expect($data, $expect_callback);

return compact('collection_download_urls');
