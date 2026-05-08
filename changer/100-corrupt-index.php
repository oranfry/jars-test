<?php

use obex\Obex;

$file = DB_HOME . '/index.lock';
$time = time();
$timeout = 2;

if (!($lock = @fopen($file, 'x'))) {
    throw new Exception("Unable to lock: could not open lock file [$file]");
}

if (!flock($lock, LOCK_EX)) {
    fclose($lock);

    throw new Exception("Unable to lock: could not acquire a lock over the lock file [$file]");
}

fwrite($lock, $time + $timeout);
fflush($lock);

info("set up an index lock due to expire in $timeout seconds. sleeping...");

sleep($timeout + 1);

fclose($lock);

info("lock expired, now stale");

// index is now in an assumed corrupt state. A new jars should notice the
// corruption and repair it

global $ids;

$data = [
    (object) [
        'type' => 'album',
        'title' => 'Evergreen',
        'artist_id' => $ids['artist']['Soccer Mommy'],
    ],
];

shuffle($data);

foreach ($data as $line) {
    change('Added album [' . $line->title . ']');
}

save_expect_albums_artists($data, $ids);

return [
    'album_artists' => require TEST_HOME . '/asset/data/album-artists-4.php',
];
