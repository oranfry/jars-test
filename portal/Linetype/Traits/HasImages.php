<?php

namespace OranFry\Jars\TestPortal\Linetype\Traits;

trait HasImages
{
    use \OranFry\HasImages\Linetypes\Traits\HasImages;

    protected function music_hasimages_init()
    {
        $this->hasimages_init();

        foreach (static::IMAGE_SIZES as $image => $details) {
            $this->fields["{$image}_image_external_id"] = function ($records) use ($image) : ?int {
                if (@$records["/{$image}_image"]->external_id) {
                    return (int) $records["/{$image}_image"]->external_id;
                }

                return null;
            };
        }
    }

    protected function music_hasimages_unpack($line, $oldline, $old_inlines)
    {
        $this->hasimages_unpack($line, $oldline, $old_inlines);

        foreach (static::IMAGE_SIZES as $image => $details) {
            if (is_object($child = @$line->{"{$image}_image"}) && $val = (int) @$line->{"{$image}_image_external_id"}) {
                $child->external_id = $val;
            }
        }
    }
}
