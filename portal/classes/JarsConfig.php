<?php

namespace music;

use jars\Sequence as Sequence;

class JarsConfig implements \jars\contract\Config
{
    private Sequence $sequence;

    function __construct()
    {
        // do nothing
    }

    public function credentialsCorrect(string $username, string $password): bool
    {
        if ($username !== 'music') {
            return false;
        }

        // return $password === '123456';
        // done better:

        $salt = 'pXAkEnaH;8T.evC4Q[cS:z5\'7*2?ruqB>Y,yU&DdJR$ZN=K%mL';
        $expected_hash = '6d07e512f62e1a3e892856f59b6af7823325c9bcb4f0d1b6e4a76df43eae0385';

        return hash('sha256', $salt . $password) === $expected_hash;
    }

    public function download_fields(): array
    {
        return [];
    }

    public function float_dp(): array
    {
        return [];
    }

    public function linetypes(): array
    {
        return [
            'album' => \music\linetype\album::class,
            'albumimageset' => \music\linetype\albumimageset::class,
            'artist' => \music\linetype\artist::class,
            'download' => \music\linetype\download::class,
            'image' => \hasimages\linetype\image::class,
            'imagemeta' => \music\linetype\imagemeta::class,
            'imagemetaplain' => \music\linetype\imagemetaplain::class,
            'imageplain' => \hasimages\linetype\imageplain::class,
            'token' => \jars\linetype\token::class,
            'track' => \music\linetype\track::class,
        ];
    }

    public function report_fields(): array
    {
        return [
            'artists' => ['name'],
            'collection' => ['title', 'artist_name'],
            'imagemetas' => ['title'],
        ];
    }

    public function reports(): array
    {
        return [
            'artists' => \music\report\artists::class,
            'collection' => \music\report\collection::class,
            'downloads' => \music\report\downloads::class,
            'imagemetas' => \music\report\imagemetas::class,
        ];
    }

    public function respect_newline_fields(): array
    {
        return [];
    }

    public function sequence(): Sequence
    {
        return $this->sequence ??= new Sequence('zYuDd1mlcYByTDJixZXPDC1MMcO3RklrejRhO55dVQw=');
    }

    public function tables(): array
    {
        return [
            'image' => (object) [
                'extension' => 'jpg',
                'type' => 'image/jpeg',
                'format' => 'binary',
            ],
        ];
    }
}
