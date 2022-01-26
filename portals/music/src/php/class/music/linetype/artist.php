<?php

namespace music\linetype;

class artist extends \Linetype
{
    function __construct()
    {
        parent::__construct();

        $this->table = 'artist';

        $this->simple_strings('name');

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
