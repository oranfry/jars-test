<?php

use OranFry\Jars\Contract\JarsConnector;
use OranFry\Jars\Core\Jars;

class TestFailedException extends Exception {}
class CouldNotTestException extends Exception {}

function do_change($change)
{
    if (JARS_TEST_VERBOSE) {
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
    $data = do_change($change);
    do_test($test, $data);
    replay();
    do_test($test, $data);
}

function do_test($name, $data)
{
    $jars = fresh_jars();
    $jars->login(USERNAME, PASSWORD, true);
    info('Refreshed to version ' . $jars->refresh());

    // $jars
    //     ->filesystem()
    //     ->persist()
    //     ->reset();

    unset($jars);

    if (JARS_TEST_VERBOSE) {
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
    if (JARS_TEST_VERBOSE >= 2) {
        message(' ', $message, "\033[90m");
    }
}

function info($message) {
    message('ℹ', $message, "\033[94m");
}

function logger($message) {
    message('✓', $message, "\033[32m");
}

function message(string $symbol, string $message, string $color)
{
    if (JARS_TEST_VERBOSE) {
        $lines = explode("\n", $message);

        echo $color;
        echo '  ' . $symbol . ' ' . array_shift($lines) . "\n";

        foreach ($lines as $line) {
            echo '    ' . $line . "\n";
        }

        echo "\033[39m";
    }
}

function save_expect(array $data, ?callable $output_callback = null, ?callable $error_callback = null)
{
    global $version;

    $jars = fresh_jars();
    $jars->login(USERNAME, PASSWORD, true);

    if (JARS_TEST_VERBOSE >= 3) {
        $json = json_encode($data, JSON_UNESCAPED_SLASHES);

        if (strlen($json) < JARS_TEST_OUTPUT_THRESHOLD) {
            fineprint('Sending to jars:');
            fineprint($json);
        } else {
            fineprint('Sending to jars: [hidden for brevity]');
        }
    }

    $output = $jars->save($data, $version ?? null);

    if (JARS_TEST_VERBOSE >= 3) {
        $json = json_encode($output, JSON_UNESCAPED_SLASHES);

        if (strlen($json) < JARS_TEST_OUTPUT_THRESHOLD) {
            fineprint('Got back from jars:');
            fineprint($json);
        } else {
            fineprint('Got back from jars: [hidden for brevity]');
        }
    }

    $version = $output[0]->version;

    unset($jars);

    if ($output_callback) {
        return $output_callback($output, $data);
    }
}

function preview_expect(array $data, ?callable $output_callback = null, ?callable $error_callback = null)
{
    global $version;

    $jars = fresh_jars();
    $jars->login(USERNAME, PASSWORD, true);

    if (JARS_TEST_VERBOSE >= 3) {
        $json = json_encode($data, JSON_UNESCAPED_SLASHES);

        if (strlen($json) < JARS_TEST_OUTPUT_THRESHOLD) {
            fineprint('Sending to jars:');
            fineprint($json);
        } else {
            fineprint('Sending to jars: [hidden for brevity]');
        }
    }

    $output = $jars->preview($data, $version ?? null);

    if (JARS_TEST_VERBOSE >= 3) {
        $json = json_encode($output, JSON_UNESCAPED_SLASHES);

        if (strlen($json) < JARS_TEST_OUTPUT_THRESHOLD) {
            fineprint('Got back from jars:');
            fineprint($json);
        } else {
            fineprint('Got back from jars: [hidden for brevity]');
        }
    }

    unset($jars);

    if ($output_callback) {
        $output_callback($output, $data);
    }
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

function jars_cmd()
{
    return "'" . BIN_HOME . "/jars' '--autoload=" . __DIR__ . "/portal/vendor/autoload.php' '--connection-string=" . CONNECTION_STRING . "' -u " . USERNAME . " -p " . PASSWORD;
}

function replay()
{
    $master = DB_HOME . '/master';
    $master_backup = '/tmp/music-master-' . getmypid();

    info('Replaying & Refreshing');

    $rootVersion = Jars::ROOT_VERSION;
    $jarsCmd = "'" . BIN_HOME . "/jars' '--autoload=" . __DIR__ . "/portal/vendor/autoload.php' '--connection-string=" . CONNECTION_STRING . "' -u " . USERNAME . " -p " . PASSWORD;

    $cmds = [
        "rm -rf '" . DB_HOME . ".bak'",
        "mv '" . DB_HOME . "' '" . DB_HOME . ".bak'",
        "mkdir '" . DB_HOME . "'",
        "mkdir '" . DB_HOME . "/chain'",
        "mkdir '" . DB_HOME . "/index'",
        "mkdir '" . DB_HOME . "/reports'",
        "mkdir '" . DB_HOME . "/master'",
        implode("\n", [
            'source_version=' . $rootVersion,
            'while true; do',
            '    file="' . DB_HOME . '.bak/master/${source_version:0:2}/${source_version:2:2}/$source_version"',
            '    if [ ! -e "$file" ]; then break; fi',
            '    base_version=$source_version',
            '    source_version="$(head -c 64 "$file")"',
            '    echo source_version: $source_version, base_version: $base_version',
            '    cat "$file" | ' . jars_cmd() . ' import $base_version',
            'done',
        ]),
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

    global $version;

    $version = fresh_jars()->head();

    // die("replayed\n");
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
    $found = [];

    foreach ($artists as $artist) {
        if (null === $pos = array_search(@$artist->name, $expected)) {
            throw new TestFailedException('Unexpected artist found: [' . @$artist->name . ']');
        }

        logger('Expected artist [' . @$artist->name . '] found');

        $found[] = $expected[$pos];

        unset($expected[$pos]);

        if ($ids['artist'][$artist->name] !== $artist->id) {
            throw new TestFailedException('Unexpected id for artist: [' . $artist->name . ']: [' . $artist->id . '], expected [' . $ids['artist'][$artist->name] . ']');
        }

        logger('Artist ID was [' . $ids['artist'][$artist->name] . '], as expected');
    }

    if (count($expected)) {
        throw new TestFailedException('Not all expected artists were found, found [' . implode(', ', $found) . '], missing [' . implode(', ', $expected) . ']');
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

function save_expect_albums_artists(array $data, &$ids = null, &$version = null): object
{
    return save_expect($data, function ($output, $original) use (&$ids, &$version) {
        if (!is_array($output)) {
            throw new TestFailedException('Output expected to be an array');
        }

        logger('Got array, as expected');

        $expected = count($original);

        if (count($output) != $expected) {
            throw new TestFailedException('Got [' . count($output) . '] elements in output, expected [' . $expected . ']');
        }

        logger('Array had ['. $expected . '] elements, as expected');

        foreach ($output as $item) {
            switch ($item->type) {
                case 'album':
                    $ids['album'][$item->title] = $item->id;
                    break;

                case 'artist':
                    $ids['artist'][$item->name] = $item->id;

                    foreach (@$item->albums ?: [] as $album) {
                        $ids['album'][$album->title] = $album->id;
                    }

                    break;

                default:
                    throw new TestFailedException('Unexpected item type [' . @$item->type . ']');
            }
        }

        $version = $output ? reset($output)->version : null;

        return (object) compact('output', 'original');
    });
}