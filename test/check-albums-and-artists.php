<?php

$artists = array_unique(array_values($album_artists));
$albums = array_keys($album_artists);

check_artist_reports($artists);
check_album_reports($albums);
check_album_artists($album_artists);
