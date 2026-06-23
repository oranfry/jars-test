<?php

namespace OranFry\Jars\TestPortal\Linetype;

class artist extends \OranFry\Jars\Core\Linetype
{
    use \simplefields\traits\SimpleFields;

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
