<?php

do_change('010-token'); // creates a token, but it is not actually used during tests

do_change_and_test('020-artists-and-albums', 'check-albums-and-artists');
do_change_and_test('021-more-artists-and-albums', 'check-albums-and-artists');
do_change_and_test('022-concurrent-modification', 'check-albums-and-artists');
do_change_and_test('030-delete', 'check-albums-and-artists');
do_change_and_test('040-add-tracks', 'check-album-tracks');
do_change_and_test('050-images', 'verify-images');
do_change_and_test('060-external-ids-forward', 'check-external-ids');
do_change_and_test('061-external-ids-back', 'check-external-ids');
do_change_and_test('070-preview', 'check-album-tracks');
do_change_and_test('080-timestamps-a', 'check-release-dates');
do_change_and_test('081-timestamps-b', 'check-release-dates');
do_change_and_test('090-inline-child-propagation-forward', 'check-downloads');
do_change_and_test('091-inline-child-propagation-back', 'check-downloads');
do_change_and_test('092-inline-child-propagation-forward-delete', 'check-downloads');
do_change_and_test('093-inline-child-propagation-back-delete-a', 'check-downloads');
do_change_and_test('094-inline-child-propagation-back-delete-b', 'check-downloads');
do_change_and_test('095-mutual-inline-child-a', 'check-downloads');
do_change_and_test('096-mutual-inline-child-b', 'check-downloads');
