<?php

use obex\Obex;

$jars = fresh_jars();
$jars->login(USERNAME, PASSWORD, true);

$collection_download_urls = require TEST_HOME . '/asset/data/download-urls-2.php';
$collection = $jars->group('collection');
$downloads = $jars->group('downloads');

$data = [];

foreach ($collection_download_urls as $album_title => $album_download_urls) {
    $album = Obex::find($collection, 'title', 'is', $album_title);

    foreach ($album_download_urls as $track_title => $download_url) {
        $track = Obex::find($album->tracks, 'title', 'is', $track_title);
        $download = Obex::find($downloads, 'id', 'is', $track->download_id);
        $download->url = $download_url;

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
        if ($download->url !== $original[$i]->url) {
            throw new TestFailedException('Track [' . @$download->id . '] came back with unexpected url [' . @$download->url . '], expected [' . $original[$i]->url . ']');
        }

        logger('Track [' . @$download->id . '] came back with correct url [' . @$download->url . ']');
    }

    logger('Download URL correct');
};

info('preview');
preview_expect($data, $expect_callback);
info('save');
save_expect($data, $expect_callback);

return compact('collection_download_urls');
