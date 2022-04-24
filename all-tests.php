<?php

do_change('001-token'); // creates a token, but it is not actually used during tests

do_change_and_test('002-artists-and-albums', 'check-albums-and-artists');
do_change_and_test('003-more-artists-and-albums', 'check-albums-and-artists');
do_change_and_test('004-delete', 'check-albums-and-artists');
do_change_and_test('005-add-tracks', 'check-album-tracks');
do_change_and_test('006-images', 'verify-images');
do_change_and_test('007-external-ids', 'check-external-ids');
do_change_and_test('008-preview', 'check-album-tracks');
do_change_and_test('009-timestamps-a', 'check-release-dates');
do_change_and_test('010-timestamps-b', 'check-release-dates');
do_change_and_test('011-inline-child-propagation-forward', 'check-downloads');
do_change_and_test('012-inline-child-propagation-back', 'check-downloads');
