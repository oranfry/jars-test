<?php

namespace music\linetype;

class album extends \Linetype
{
    function __construct()
    {
        parent::__construct();

        $this->table = 'album';

        $this->simple_strings('title');

        $this->inlinelinks = [
            (object) [
                'property' => 'artist',
                'linetype' => 'artist',
                'tablelink' => 'artist_album',
                'reverse' => true,
                'orphanable' => true,
            ],
        ];

        $this->borrow = [
            'artist_name' => fn ($line) : ?string => @$line->artist->name,
        ];
    }

    public function unpack($line, $oldline, $old_inlines)
    {
        parent::unpack($line, $oldline, $old_inlines);

        $line->artist = 'unchanged';
    }
}
