<?php

namespace music\report;

class collection extends \OranFry\Jars\Core\Report
{
    public function __construct()
    {
        $this->listen = [
            'album' => (object) [
                'children' => [
                    'tracks' => (object) [
                        'sorter' => fn ($a, $b) => $a->number <=> $b->number,
                    ],
                ],
            ],
        ];

        $this->sorter = fn (object $a, object $b) => strnatcasecmp($a->title, $b->title);
    }
}
