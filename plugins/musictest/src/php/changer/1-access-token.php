<?php

save_expect([(object) [
    'type' => 'token',
    'token' => '0a1d029281fc6623130266c76291e1162ce1d53e5e9a6b6f28d3a56796105249',
    'ttl' => 999999999,
]], function ($returned, $given) {
    if (!is_array($returned)) {
        throw new TestFailedException('Output expected to be an array');
    }

    logger('Response was an array');

    if (count($returned) != count($given)) {
        throw new TestFailedException('Wrong number of tokens returned: got [' . count($returned) . '], expected [' . count($given) . ']');
    }

    logger('Expected number of tokens returned: [' . count($given) . ']');

    $given_token = reset($given);
    $returned_token = reset($returned);

    if ($returned_token->token !== $given_token->token) {
        throw new TestFailedException('Token came back different, got [' . $returned_token->token . '], expected [' . $given_token->token . ']');
    }

    logger('Expected token returned: [' . $given_token->token . ']');

    if ($returned_token->ttl !== $given_token->ttl) {
        throw new TestFailedException('Token ttl came back different, got [' . $returned_token->ttl . '], expected [' . $given_token->ttl . ']');
    }

    logger('Expected ttl returned: [' . $given_token->ttl . ']');
});
