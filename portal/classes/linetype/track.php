<?php

namespace music\linetype;

class track extends \jars\Linetype
{
    function __construct()
    {
        parent::__construct();

        $this->table = 'track';

        $this->simple_ints('number');
        $this->simple_strings('title');

        $this->borrow = [
            'download_url' => fn ($line) => $line->download->url ?? null,
            'download_id' => fn ($line) : ?string => @$line->download->id,
        ];

        $this->inlinelinks = [
            (object) [
                'linetype' => 'download',
                'property' => "download",
                'tablelink' => "track_download",
            ],
        ];
    }

    public function unpack($line, $oldline, $old_inlines)
    {
        parent::unpack($line, $oldline, $old_inlines);

        if (@$line->download_url) {
            $line->download = (object) [
                'url' => $line->download_url,
            ];
        }
    }
}
