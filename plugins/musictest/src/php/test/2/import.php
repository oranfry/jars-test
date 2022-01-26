<?php

save_expect('2-data', function ($output) {
    global $artist_ids, $album_ids;

    if (!is_array($output)) {
        throw new TestFailedException('Output expected to be an array');
    }

    logger('Got array, as expected');

    $expected = 3;

    if (count($output) != $expected) {
        throw new TestFailedException('Got [' . count($output) . '] elements in output, expected [' . $expected . ']');
    }

    logger('Array had ['. $expected . '] elements, as expected');

    foreach ($output as $item) {
        switch ($item->type) {
            case 'album':
                $album_ids[$item->title] = $item->id;
                break;

            case 'artist':
                $artist_ids[$item->name] = $item->id;

                foreach ($item->albums as $album) {
                    $album_ids[$album->title] = $album->id;
                }

                break;

            default:
                throw new TestFailedException('Unexpected item type [' . @$item->type . ']');
        }
    }
});

