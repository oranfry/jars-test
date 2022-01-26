<?php

namespace music\config;

class base
{
    function __construct()
    {
        $this->instance_name = 'Music';
        $this->root_password = '983dk32dfmfa1s';
        $this->root_username = 'music';

        $this->linetypes = [
            'album' => (object) ['class' => 'music\linetype\album'],
            'artist' => (object) ['class' => 'music\linetype\artist'],
            'track' => (object) ['class' => 'music\linetype\track'],
        ];

        $this->reports = [
            'artists' => 'music\report\artists',
            'collection' => 'music\report\collection',
        ];

        $this->sequence = (object) [
            'secret' => 'zYuDd1mlcYByTDJixZXPDC1MMcO3RklrejRhO55dVQw=',
            'banned_chars' => ['/', '=', '+', '1', '0', 's', 'S', '5', 'j', 'J'],
            'max' => 100000000,
            'collisions' => [],
            'subs' => [],
        ];
    }
}
