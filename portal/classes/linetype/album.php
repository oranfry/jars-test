<?php

namespace music\linetype;

use DateTime;

class album extends \jars\Linetype
{
    use \simplefields\traits\SimpleFields;
    use traight\hasimages;

    const IMAGE_SIZES = [
        'cover' => ['size' => [1200, 1200]],
    ];

    function __construct()
    {
        parent::__construct();

        $this->table = 'album';

        $this->simple_string('title');
        $this->simple_string('comment');
        $this->simple_string('released');
        $this->simple_int('timestamp');

        $this->borrow = [
            'artist_name' => fn ($line) : ?string => @$line->artist->name,
            'age' => fn ($line) : ?int => @$line->released ? (int) (new DateTime(date('Y-m-d', $line->timestamp)))->diff(new DateTime($line->released))->format('%y') : null,
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

    public function complete($line) : void
    {
        if (!@$line->timestamp) {
            $line->timestamp = time();
        }
    }

    public function unpack($line, $oldline, $old_inlines)
    {
        parent::unpack($line, $oldline, $old_inlines);

        $line->artist = 'unchanged';
        $line->comment = $line->title;

        $this->music_hasimages_unpack($line, $oldline, $old_inlines);
    }
}
