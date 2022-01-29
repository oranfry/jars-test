<?php

namespace music\linetype;

class album extends \Linetype
{
    use traight\hasimages;

    const IMAGE_SIZES = [
        'cover' => ['size' => [1200, 1200]],
    ];

    function __construct()
    {
        parent::__construct();

        $this->table = 'album';

        $this->simple_strings('title', 'comment');

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

        $this->music_hasimages_init();
    }

    public function unpack($line, $oldline, $old_inlines)
    {
        parent::unpack($line, $oldline, $old_inlines);

        $line->artist = 'unchanged';
        $line->comment = $line->title;

        $this->music_hasimages_unpack($line, $oldline, $old_inlines);
    }
}
