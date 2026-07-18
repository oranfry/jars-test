<?php

namespace OranFry\Jars\TestPortal\Linetype;

class Download extends \OranFry\Jars\Core\Linetype
{
    use \OranFry\SimpleFields\Traits\SimpleFields;

    function __construct()
    {
        parent::__construct();

        $this->table = 'download_r';

        $this->simple_string('url');

        $this->inlinelinks = [
            (object) [
                'linetype' => 'track',
                'property' => "track",
                'tablelink' => "track_download",
                'reverse' => true,
                'orphanable' => true,
            ],
        ];
    }

    public function unpack($line, $oldline, $old_inlines)
    {
        parent::unpack($line, $oldline, $old_inlines);

        $line->track = 'unchanged';
    }
}
