<?php

namespace music\report;

class collection extends \Report
{
    public function __construct()
    {
        $this->listen = [
            'album' => (object) [
                'children' => [
                    'tracks' => (object) ['sorter' => object_int_comparator('number')],
                ],
            ],
        ];

        $this->sorter = fn (object $a, object $b) => strnatcasecmp($a->title, $b->title);
    }
}
