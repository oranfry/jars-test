<?php

namespace OranFry\Jars\TestPortal\Linetype;

class AlbumImageSet extends ImageSet
{
    function __construct()
    {
        $this->table = 'album_r';
        $this->image_sizes = album::IMAGE_SIZES;

        parent::__construct();
    }
}
