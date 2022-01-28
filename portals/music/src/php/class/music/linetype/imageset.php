<?php

namespace music\linetype;

class imageset extends \hasimages\linetype\imageset
{
    function __construct()
    {
        parent::__construct();

        foreach ($this->image_sizes as $image => $details) {
            $this->borrow["{$image}_image_external_id"] = function ($line) use ($image) : ?int {
                $val = @$line->{"{$image}_image"}->external_id;

                return $val ? (int) $val : null;
            };
        }
    }

    public function unpack($line, $oldline, $old_inlines)
    {
        parent::unpack($line, $oldline, $old_inlines);

        foreach ($this->image_sizes as $image => $details) {
            if (is_object($child = @$line->{"{$image}_image"}) && $val = (int) @$line->{"{$image}_image_external_id"}) {
                $child->external_id = $val;
            }
        }
    }
}
