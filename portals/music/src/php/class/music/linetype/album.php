<?php

namespace music\linetype;

class album extends \Linetype
{
    function __construct()
    {
        parent::__construct();

        $this->table = 'album';

        $this->simple_strings('title');

        $this->borrow = [
            'artist_name' => fn ($line) : ?string => @$line->artist->name,
        ];

        $this->inlinelinks = [
            (object) [
                'property' => 'artist',
                'linetype' => 'artist',
                'tablelink' => 'artist_album',
                'reverse' => true,
                'orphanable' => true,
            ],
        ];

        $this->children = [
            (object) [
                'property' => 'tracks',
                'linetype' => 'track',
                'tablelink' => 'album_track',
                'only_parent' => 'album_id',
            ],
        ];
    }

    public function unpack($line, $oldline, $old_inlines)
    {
        parent::unpack($line, $oldline, $old_inlines);

        $line->artist = 'unchanged';
    }
}
