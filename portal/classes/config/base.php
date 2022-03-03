<?php

namespace music\config;

class base
{
    function __construct()
    {
        $this->instance_name = 'Music';
        $this->root_password = '123456';
        $this->root_username = 'music';

        $this->linetypes = [
            'album' => (object) ['class' => 'music\linetype\album'],
            'albumimageset' => (object) ['class' => 'music\linetype\albumimageset'],
            'artist' => (object) ['class' => 'music\linetype\artist'],
            'image' => (object) ['class' => 'hasimages\linetype\image'],
            'imagemeta' => (object) ['class' => 'music\linetype\imagemeta'],
            'imagemetaplain' => (object) ['class' => 'music\linetype\imagemetaplain'],
            'imageplain' => (object) ['class' => 'hasimages\linetype\imageplain'],
            'track' => (object) ['class' => 'music\linetype\track'],
        ];

        $this->reports = [
            'artists' => 'music\report\artists',
            'collection' => 'music\report\collection',
            'imagemetas' => 'music\report\imagemetas',
        ];

        $this->sequence = (object) [
            'secret' => 'zYuDd1mlcYByTDJixZXPDC1MMcO3RklrejRhO55dVQw=',
            'banned_chars' => ['/', '=', '+', '1', '0', 's', 'S', '5', 'j', 'J'],
            'max' => 100000000,
            'collisions' => [],
            'subs' => [],
        ];

        $this->tables = [
            'image' => (object) [
                'extension' => 'jpg',
                'type' => 'image/jpeg',
                'format' => 'binary',
            ],
        ];

        $this->report_fields = [
            'artists' => ['name'],
            'collection' => ['title', 'artist_name'],
            'imagemetas' => ['title'],
        ];
    }
}
