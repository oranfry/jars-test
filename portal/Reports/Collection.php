<?php

namespace OranFry\Jars\TestPortal\Reports;

class Collection extends \OranFry\Jars\Core\Report
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
