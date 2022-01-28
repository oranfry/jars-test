<?php

namespace music\linetype;

class albumimageset extends imageset
{
    function __construct()
    {
        $this->table = 'album';
        $this->image_sizes = album::IMAGE_SIZES;

        parent::__construct();
    }
}
