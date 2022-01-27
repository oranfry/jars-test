<?php

namespace music\report;

class collection extends \Report
{
    public function __construct()
    {
        $this->listen = [
            'album' => (object) [
                /*'children' => [
                    'tracks' => (object) ['sorter' => object_int_comparator('number')],
                ],*/
                'sorter' => object_string_comparator('title'),
            ],
        ];

        $this->sorter = object_string_comparator('title');
    }
}
