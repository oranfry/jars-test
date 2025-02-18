<?php

namespace music\linetype;

class track extends \jars\Linetype
{
    use \simplefields\traits\SimpleFields;

    function __construct()
    {
        parent::__construct();

        $this->table = 'track_r';

        $this->simple_int('number');
        $this->simple_string('title');

        $this->borrow = [
            'download_url' => fn ($line): ?string => $line->download->url ?? null,
            'download_id' => fn ($line): ?string => $line->download->id ?? null,
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
