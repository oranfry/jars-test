<?php

do_change('1-access-token');
do_change_and_test('2-artists-and-albums', 'check-albums-and-artists');
do_change_and_test('3-more-artists-and-albums', 'check-albums-and-artists');
do_change_and_test('4-delete', 'check-albums-and-artists');
do_change_and_test('5-add-tracks', 'check-album-tracks');
do_change_and_test('6-images', 'verify-images');
do_change_and_test('7-external-ids', 'check-external-ids');
