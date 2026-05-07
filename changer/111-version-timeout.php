<?php

use obex\Obex;
use OranFry\Jars\Contract\VersionTimeoutException;

global $ids;

$artistName = 'Soccer Mommy';
$albumName = 'Sometimes, Forever';

$data = [(object) [
    'type' => 'album',
    'title' => $albumName,
    'artist_id' => $ids['artist'][$artistName],
]];

shuffle($data);

foreach ($data as $line) {
    change('Added album [' . $line->title . ']');
}

save_expect_albums_artists($data, $ids, $wait_version);

// on this occasion we will stop and wait for a version of reports that isn't
// coming

$jars = fresh_jars();
$jars->login(USERNAME, PASSWORD, true);

info('Start to wait for version ' . $wait_version . ' that will never come, with 3s timeout');

try {
    $collection = $jars->group('collection', '', $wait_version, 3000000);
    throw new TestFailedException('Did not catch a VersionTimeoutException');
} catch (VersionTimeoutException $vte) {
    logger('Caught a VersionTimeoutException as expected');
}

return [
    'album_artists' => require TEST_HOME . '/asset/data/album-artists-6.php',
];
