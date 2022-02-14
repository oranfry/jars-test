<?php

use jars\Jars;

function check_album_artists($expected)
{
    global $ids;

    info(__METHOD__);

    $version = refresh_reports();
    info('version: ' . $version);

    $jars = Jars::of(PORTAL_HOME, DB_HOME);
    $jars->login(USERNAME, PASSWORD, true);
    $collection = $jars->group('collection', 'all', $version);

    foreach ($collection as $album) {
        $expected_artist_id = @$ids['artist'][@$expected[$album->title]];

        if (!$expected_artist_id) {
            throw new TestFailedException('Could not determine expected artist id for album [' . $album->title . ']');
        }

        if ($expected_artist_id != $album->artist_id) {
            throw new TestFailedException('Unexpected artist_id [' . $album->artist_id . '], expected [' . $expected_artist_id . '] for album [' . $album->title . ']');
        }

        logger('Found expected artist id [' . $expected_artist_id . '] for album [' . $album->title . ']');

        if ($album->artist_name != @$expected[$album->title]) {
            throw new TestFailedException('Unexpected artist_name [' . $album->artist_name . '], expected [' . @$expected[$album->title] . '] for album [' . $album->title . ']');
        }

        logger('Found expected artist name [' . @$expected[$album->title] . '] for album [' . $album->title . ']');
    }

    $jars->filesystem()->persist();
    $jars->filesystem()->revert();
}

function check_album_records($expected)
{
    global $ids;

    info(__METHOD__);

    $records_path = DB_HOME . '/current/records/album';

    if (!is_dir($records_path)) {
        throw new TestFailedException('records_path not found: [' . $records_path . ']');
    }

    $files = explode("\n", trim(`ls -1 '$records_path'`));
    $expected_records = count($expected);

    if ($expected_records !== $count = count($files)) {
        throw new TestFailedException('Album records_path contains not [' . $expected_records . '] files, but [' . $count . ']');
    }

    foreach ($files as $file) {
        $id = preg_replace('/\.json$/', '', $file);

        try {
            $record = json_decode(file_get_contents($path = $records_path . '/' . $file), false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new TestFailedException('File does not contain valid JSON: [' . $path . ']');
        }

        logger('File contains valid JSON: [' . $path . ']');

        if (!is_object($record)) {
            throw new TestFailedException('JSON did not decode to an object in : [' . $path . ']');
        }

        logger('JSON decoded to an object');

        if (!property_exists($record, 'title')) {
            throw new TestFailedException('Album record missing title: [' . $path . ']');
        }

        logger('Album has a title');

        if ($id !== @$ids['album'][$record->title]) {
            throw new TestFailedException('Unexpected Album ID: [' . $id . '], expected [' . @$ids['album'][$record->title] . '] for album [' . $record->title . ']');
        }

        if (false === $pos = array_search($record->title, $expected)) {
            throw new TestFailedException('Unexpected album found: [' . $record->title . ']');
        }

        logger('Found album was expected');

        unset($expected[$pos]);
    }

    if (count($expected)) {
        throw new TestFailedException('Expected album(s) not found: [' . print_r($expected, true) . ']');
    }

    logger('Found all expected albums, and no unexpected ones');
}

function check_album_reports($expected)
{
    global $ids;

    info(__METHOD__);

    $version = refresh_reports();
    info('version: ' . $version);

    $jars = Jars::of(PORTAL_HOME, DB_HOME);
    $jars->login(USERNAME, PASSWORD, true);
    $groups = $jars->groups('collection', $version);

    if (count($groups) != 1) {
        throw new TestFailedException('Found [' . count($groups) . '] collection groups, expected 1');
    }

    logger('Found 1 collection group, as expected');

    if (reset($groups) != 'all') {
        throw new TestFailedException('Expected group to be called [all], got [' . reset($groups) . ']');
    }

    logger('Group is called [all], as expected');

    $collection = $jars->group('collection', 'all', $version);

    // usleep(1000000);
    // $f = '/home/oran/Unsynched/dev/jars-test/db/music/reports/collection/all.json';
    // var_dump($jars->filesystem()->getStore());
    // echo $jars->filesystem()->get($f) . "\n\n";
    // echo file_get_contents($f) . "\n\n";
    // var_dump($collection);

    foreach ($collection as $album) {
        if (null === $pos = array_search(@$album->title, $expected)) {
            throw new TestFailedException('Unexpected album found: [' . @$album->title . ']');
        }

        logger('Expected album [' . @$album->title . '] found');

        unset($expected[$pos]);

        if ($ids['album'][$album->title] !== $album->id) {
            throw new TestFailedException('Unexpected id for album: [' . $album->title . ']: [' . $album->id . '], expected [' . $ids['album'][$album->title] . ']');
        }

        logger('Album ID was [' . $ids['album'][$album->title] . '], as expected');
    }

    if (count($expected)) {
        throw new TestFailedException('Not all expected albums were found, missing [' . implode(', ', $expected) . ']');
    }

    logger('All expected albums found, and no unexpected ones');

    $jars->filesystem()->persist();
    $jars->filesystem()->revert();
}

function check_artist_records($expected)
{
    global $ids;

    info(__METHOD__);

    $records_path = DB_HOME . '/current/records/artist';

    if (!is_dir($records_path)) {
        throw new TestFailedException('records_path not found: [' . $records_path . ']');
    }

    $files = explode("\n", trim(`ls -1 '$records_path'`));
    $expected_records = count($expected);

    if ($expected_records !== $count = count($files)) {
        throw new TestFailedException('Artist records_path contains not [' . $expected_records . '] files, but [' . $count . ']');
    }

    logger('Found correct number of records: [' . $expected_records . ']');

    foreach ($files as $file) {
        $id = preg_replace('/\.json$/', '', $file);

        try {
            $record = json_decode(file_get_contents($path = $records_path . '/' . $file), false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new TestFailedException('File does not contain valid JSON: [' . $path . ']');
        }

        logger('File contains valid JSON: [' . $path . ']');

        if (!is_object($record)) {
            throw new TestFailedException('JSON did not decode to an object in : [' . $path . ']');
        }

        logger('JSON decoded to an object');

        if (!property_exists($record, 'name')) {
            throw new TestFailedException('Artist record missing name: [' . $path . ']');
        }

        logger('Artist has a name');

        if (false === $pos = array_search($record->name, $expected)) {
            throw new TestFailedException('Unexpected artist found: [' . $record->name . ']');
        }

        logger('Found artist was expected');

        if ($id !== $ids['artist'][$record->name]) {
            throw new TestFailedException('Unexpected Artist ID: [' . $id . '], expected [' . $ids['artist'][$record->name] . '] for artist [' . $record->name . ']');
        }

        logger('Artist ID was [' . $ids['artist'][$record->name] . '], as expected');

        unset($expected[$pos]);
    }

    if (count($expected)) {
        throw new TestFailedException('Expected artist(s) not found: [' . print_r($expected, true) . ']');
    }

    logger('Found all expected artists, and no unexpected ones');
}

function check_artist_reports($expected)
{
    global $ids;

    info(__METHOD__);

    $version = refresh_reports();
    info('version: ' . $version);

    $jars = Jars::of(PORTAL_HOME, DB_HOME);
    $jars->login(USERNAME, PASSWORD, true);
    $groups = $jars->groups('artists', $version);

    if (count($groups) != 1) {
        throw new TestFailedException('Found [' . count($groups) . '] artist groups, expected 1');
    }

    logger('Found 1 artist group, as expected');

    if (reset($groups) != 'all') {
        throw new TestFailedException('Expected group to be called [all], got [' . reset($groups) . ']');
    }

    logger('Group is called [all], as expected');

    $artists = $jars->group('artists', 'all', $version);

    foreach ($artists as $artist) {
        if (null === $pos = array_search(@$artist->name, $expected)) {
            throw new TestFailedException('Unexpected artist found: [' . @$artist->name . ']');
        }

        logger('Expected artist [' . @$artist->name . '] found');

        unset($expected[$pos]);

        if ($ids['artist'][$artist->name] !== $artist->id) {
            throw new TestFailedException('Unexpected id for artist: [' . $artist->name . ']: [' . $artist->id . '], expected [' . $ids['artist'][$artist->name] . ']');
        }

        logger('Artist ID was [' . $ids['artist'][$artist->name] . '], as expected');
    }

    if (count($expected)) {
        throw new TestFailedException('Not all expected artists were found, missing [' . implode(', ', $expected) . ']');
    }

    logger('All expected artists found, and no unexpected ones');

    $jars->filesystem()->persist();
    $jars->filesystem()->revert();
}

function do_change($change)
{
    if (VERBOSE) {
        echo "\033[37m";
        echo "\n\n" . ' Running change [' . $change . ']' . "\n\n";
        echo "\033[39m";
    }

    $changefile = TEST_HOME . '/changer/' . $change . '.php';

    if (!is_file($changefile)) {
        throw new CouldNotTestException('Test [' . $change . '] does not exist');
    }

    return require $changefile;
}

function do_change_and_test($change, $test)
{
    do_test($test, $data = do_change($change));
    replay();
    do_test($test, $data);
}

function do_test($name, $data)
{
    if (VERBOSE) {
        echo "\033[37m";
        echo "\n\n" . ' Running test [' . $name . ']' . "\n\n";
        echo "\033[39m";
    }

    $testfile = TEST_HOME . '/test/' . $name . '.php';

    if (!is_file($testfile)) {
        throw new CouldNotTestException('Test [' . $name . '] does not exist');
    }

    extract($data);

    require $testfile;
}

function logger($message) {
    if (VERBOSE) {
        echo "\033[32m";
        echo '  ✓ ' . $message . "\n";
        echo "\033[39m";
    }
}

function info($message) {
    if (VERBOSE) {
        echo "\033[94m";
        echo '  ⓘ ' . $message . "\n";
        echo "\033[39m";
    }
}

function fineprint($message) {
    if (VERBOSE) {
        echo "\033[90m";
        echo '    ' . $message . "\n";
        echo "\033[39m";
    }
}

function refresh_reports()
{
    info('Refreshing');
    return trim(shell_exec('AUTH_TOKEN=' . AUTH_TOKEN  . ' PORTAL_HOME=' . PORTAL_HOME . ' DB_HOME=' . DB_HOME . ' /home/oran/Unsynched/dev/usr/local/jars-refreshd/refresh.php'));
}

function save_expect(array $data, callable $output_callback = null, callable $error_callback = null)
{
    $jars = Jars::of(PORTAL_HOME, DB_HOME);
    $jars->login(USERNAME, PASSWORD, true);

    $output = $jars->save($data);

    if ($output_callback) {
        $output_callback($output, $data);
    }

    $jars->filesystem()->persist();
    $jars->filesystem()->revert();

    unset($jars);
}

function strip_non_scalars(array $objectArray)
{
    foreach ($objectArray as $object) {
        foreach (array_keys(get_object_vars($object)) as $key) {
            if (!is_null($object->$key) && !is_scalar($object->$key)) {
                unset($object->$key);
            }
        }
    }
}

function replay()
{
    $master = DB_HOME . '/master.dat';
    $master_backup = tempnam('/tmp', 'music-master-');

    info('Replaying');

    $cmds = [
        "mv '" . $master . "' '" . $master_backup . "'",
        "rm -rf '" . DB_HOME . "'",
        "mkdir -p '" . DB_HOME . "'",
        "touch '" . $master . "'",
        "cat '" . $master_backup . "' | jars '--portal-home=" . PORTAL_HOME . "' '--db-home=" . DB_HOME . "' import -u " . USERNAME . " -p " . PASSWORD,
    ];

    $output = shell_exec(implode('; ', $cmds));

    foreach (explode("\n", $output) as $line) {
        fineprint($line);
    }
}

class TestFailedException extends Exception {}
class CouldNotTestException extends Exception {}
