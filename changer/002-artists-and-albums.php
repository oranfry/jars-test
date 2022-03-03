<?php

global $ids;

$album_artists = require TEST_HOME . '/asset/data/album-artists-1.php';

$artists = array_unique(array_values($album_artists));
$albums = array_keys($album_artists);
$artist_data = array_map(fn ($name) => (object) ['name' => $name, 'type' => 'artist'], $artists);

shuffle($artist_data);

save_expect($artist_data, function ($output) use ($artists, &$ids) {
    if (!is_array($output)) {
        throw new TestFailedException('Output expected to be an array');
    }

    logger('Response was an array');

    foreach ($output as $item) {
        if (!@$item->id) {
            throw new TestFailedException('Artist missing id');
        }

        if (false === $pos = array_search(@$item->name, $artists)) {
            throw new TestFailedException('Unexpected artist found [' . @$item->name . ']');
        }

        $ids['artist'][$item->name] = $item->id;
        unset($artists[$pos]);

        logger('Found artist [' . $item->name . '] with id [' . $item->id . ']');
    }

    if (count($artists)) {
        throw new TestFailedException('Not all expected artists found');
    }

    logger('All expected artists found');
});

$album_data = array_map(fn ($album_title, $artist_name) => (object) ['type' => 'album', 'title' => $album_title, 'artist_id' => $ids['artist'][$artist_name]], array_keys($album_artists), $album_artists);

save_expect($album_data, function ($output) use ($albums, &$ids) {
    if (!is_array($output)) {
        throw new TestFailedException('Output expected to be an array');
    }

    logger('Response was an array');

    foreach ($output as $item) {
        if (!@$item->id) {
            throw new TestFailedException('Album missing id');
        }

        if (false === $pos = array_search(@$item->title, $albums)) {
            throw new TestFailedException('Unexpected album found [' . @$item->title . ']');
        }

        $ids['album'][$item->title] = $item->id;
        unset($albums[$pos]);

        logger('Found expected album [' . $item->title . '] with id [' . $item->id . ']');
    }

    if (count($albums)) {
        throw new TestFailedException('Not all expected albums found, missing [' . implode(', ', $albums) . ']');
    }

    logger('All expected albums found');
});

return compact('album_artists');
