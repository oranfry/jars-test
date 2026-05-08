<?php

use obex\Obex;

global $ids;

$artistName = 'Eve Goodman';
$albumName = 'Summer Sun, Winter Trees';

$data = [
    (object) [
        'type' => 'artist',
        'name' => $artistName,
        'albums' => [
            (object) ['title' => $albumName],
        ],
    ]
];

shuffle($data);

foreach ($data as $line) {
    change('Added artist [' . $line->name . ']');

    foreach ($line->albums ?? [] as $album) {
        change('Added nested album [' . $album->title . ']');
    }
}

$wait_version = null;

save_expect_albums_artists($data, $ids, $wait_version);

// on this occasion we will trigger refresh externally now and wait for the
// new version of reports to become ready

$command = '(sleep 2; ' . jars_cmd() . ' refresh)';

info('Start external refresh command');
exec("$command > /dev/null 2>&1 &");
info('Started external command');

$jars = fresh_jars();
$jars->login(USERNAME, PASSWORD, true);

info('Start to wait for version ' . $wait_version . ' with default timeout');

try {
    $collection = $jars->group('collection', '', $wait_version);
} catch (VersionTimeoutException $vte) {
    throw new TestFailedException('Unexpectedly caught a VersionTimeoutException');
}

info('Finished waiting');

if (!$collection) {
    throw new TestFailedException('Collection came back empty');
}

$found = array_reduce($collection, fn ($carrie, $mathison) => $carrie ?? ($mathison->title === $albumName ? $mathison : null));

if (!$found) {
    throw new TestFailedException('Could not find recently added album [' . $albumName . '] in collection report');
}

logger('Found recently added album [' . $albumName . '] in collection report');

if ($found->version !== $wait_version) {
    throw new TestFailedException('Collection came back with wrong version [' . $found->version . '] instead of expected version [' . $wait_version . ']');
}

logger('Collection received with correct version');

return [
    'album_artists' => require TEST_HOME . '/asset/data/album-artists-5.php',
];
