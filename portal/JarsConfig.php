<?php

namespace OranFry\Jars\TestPortal;

use OranFry\Jars\Core\Sequence;

class JarsConfig implements \OranFry\Jars\Contract\Config
{
    private Sequence $sequence;

    function __construct()
    {
        // do nothing
    }

    public function credentialsCorrect(?string $username = null, ?string $password = null): bool
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
            'album' => Linetype\album::class,
            'albumimageset' => Linetype\albumimageset::class,
            'artist' => Linetype\artist::class,
            'download' => Linetype\download::class,
            'image' => \OranFry\HasImages\Linetype\image::class,
            'imagemeta' => Linetype\imagemeta::class,
            'imagemetaplain' => Linetype\imagemetaplain::class,
            'imageplain' => \OranFry\HasImages\Linetype\imageplain::class,
            'token' => \OranFry\Jars\Core\Linetype\token::class,
            'track' => Linetype\track::class,
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
            'artists' => Report\artists::class,
            'collection' => Report\collection::class,
            'downloads' => Report\downloads::class,
            'imagemetas' => Report\imagemetas::class,
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
