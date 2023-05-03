<?php

namespace music;

class JarsConfig
{
    public $linetypes;
    public $report_fields;
    public $reports;
    public $root_password = '123456';
    public $root_username = 'music';
    public $sequence;
    public $tables;

    function __construct()
    {
        $this->linetypes = [
            'album' => \music\linetype\album::class,
            'albumimageset' => \music\linetype\albumimageset::class,
            'artist' => \music\linetype\artist::class,
            'download' => \music\linetype\download::class,
            'image' => \hasimages\linetype\image::class,
            'imagemeta' => \music\linetype\imagemeta::class,
            'imagemetaplain' => \music\linetype\imagemetaplain::class,
            'imageplain' => \hasimages\linetype\imageplain::class,
            'track' => \music\linetype\track::class,
        ];

        $this->reports = [
            'artists' => \music\report\artists::class,
            'collection' => \music\report\collection::class,
            'downloads' => \music\report\downloads::class,
            'imagemetas' => \music\report\imagemetas::class,
        ];

        $this->sequence = (object) [
            'secret' => 'zYuDd1mlcYByTDJixZXPDC1MMcO3RklrejRhO55dVQw=',
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
