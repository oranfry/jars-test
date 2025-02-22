<?php

use jars\contract\JarsConnector;

class TestFailedException extends Exception {}
class CouldNotTestException extends Exception {}

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
    $jars = fresh_jars();
    $jars->login(USERNAME, PASSWORD, true);
    info('Refreshed to version ' . $jars->refresh());

    $jars
        ->filesystem()
        ->persist()
        ->reset();

    unset($jars);

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

function change($message) {
    message('δ', $message, "\033[33m");
}

function fineprint($message) {
    message(' ', $message, "\033[90m");
}

function info($message) {
    message('ℹ', $message, "\033[94m");
}

function logger($message) {
    message('✓', $message, "\033[32m");
}

function message(string $symbol, string $message, string $color)
{
    if (VERBOSE) {
        echo $color;
        echo '  ' . $symbol . ' ' . $message . "\n";
        echo "\033[39m";
    }
}

function save_expect(array $data, callable $output_callback = null, callable $error_callback = null)
{
    global $version;

    $jars = fresh_jars();
    $jars->login(USERNAME, PASSWORD, true);

    $output = $jars->save($data, $version ?? null);
    $version = $jars->version();

    $jars
        ->filesystem()
        ->persist()
        ->reset();

    unset($jars);

    if ($output_callback) {
        $output_callback($output, $data);
    }
}

function preview_expect(array $data, callable $output_callback = null, callable $error_callback = null)
{
    $jars = fresh_jars();
    $jars->login(USERNAME, PASSWORD, true);

    $output = $jars->preview($data);

    if ($output_callback) {
        $output_callback($output, $data);
    }

    $jars
        ->filesystem()
        ->persist()
        ->reset();

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
    $master_backup = '/tmp/music-master-' . getmypid();

    info('Replaying & Refreshing');

    $cmds = [
        "mv '" . $master . "' '" . $master_backup . "'",
        "rm -rf '" . DB_HOME . "'",
        "mkdir -p '" . DB_HOME . "'",
        "touch '" . $master . "'",
        "cat '" . $master_backup . "' | '" . BIN_HOME . "/jars' '--autoload=" . __DIR__ . "/portal/vendor/autoload.php' '--connection-string=" . CONNECTION_STRING . "' -u " . USERNAME . " -p " . PASSWORD . ' import',
        "rm '" . $master_backup . "'",
    ];

    foreach ($cmds as $cmd) {
        $output = null;

        fineprint('Running command: ' . $cmd);
        exec($cmd, $output, $code);

        foreach ($output as $line) {
            fineprint($line);
        }

        if ($code !== 0) {
            die('Error executing a replay command');
        }
    }
}

function fresh_jars()
{
    return JarsConnector::connect(CONNECTION_STRING);
}

function check_artist_reports($expected)
{
    global $ids;

    info(__METHOD__);

    $jars = fresh_jars();
    $jars->login(USERNAME, PASSWORD, true);

    $groups = $jars->groups('artists');

    if (count($groups) != 1) {
        throw new TestFailedException('Found [' . count($groups) . '] artist groups, expected 1');
    }

    logger('Found 1 artist group, as expected');

    if (reset($groups) != '') {
        throw new TestFailedException('Expected group to be called [], got [' . reset($groups) . ']');
    }

    logger('Group is called [], as expected');

    $artists = $jars->group('artists');

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
}

function check_album_reports($expected)
{
    global $ids;

    info(__METHOD__);

    $jars = fresh_jars();
    $jars->login(USERNAME, PASSWORD, true);

    $groups = $jars->groups('collection');

    if (count($groups) != 1) {
        throw new TestFailedException('Found [' . count($groups) . '] collection groups, expected 1');
    }

    logger('Found 1 collection group, as expected');

    if (reset($groups) != '') {
        throw new TestFailedException('Expected group to be called [], got [' . reset($groups) . ']');
    }

    logger('Group is called [], as expected');

    $collection = $jars->group('collection');

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
}

function check_album_artists($expected)
{
    global $ids;

    info(__METHOD__);

    $jars = fresh_jars();
    $jars->login(USERNAME, PASSWORD, true);

    $collection = $jars->group('collection');

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

        unset($expected[$album->title]);
    }

    if (count($expected)) {
        throw new TestFailedException('Not all expected albums were found, missing [' . implode(', ', array_keys($expected)) . ']');
    }

    logger('All expected albums found, and no unexpected ones');
}
