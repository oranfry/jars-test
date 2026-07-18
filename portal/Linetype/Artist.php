<?php

namespace OranFry\Jars\TestPortal\Linetype;

class Artist extends \OranFry\Jars\Core\Linetype
{
    use \OranFry\SimpleFields\Traits\SimpleFields;

    function __construct()
    {
        parent::__construct();

        $this->table = 'artist_r';

        $this->simple_string('name');

        $this->children = [
            (object) [
                'property' => 'albums',
                'linetype' => 'album',
                'tablelink' => 'artist_album',
                'only_parent' => 'artist_id',
            ],
        ];
    }
}
