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
            'album' => Linetypes\Album::class,
            'albumimageset' => Linetypes\AlbumImageSet::class,
            'artist' => Linetypes\Artist::class,
            'download' => Linetypes\Download::class,
            'image' => \OranFry\HasImages\Linetypes\Image::class,
            'imagemeta' => Linetypes\ImageMeta::class,
            'imagemetaplain' => Linetypes\ImageMetaPlain::class,
            'imageplain' => \OranFry\HasImages\Linetypes\ImagePlain::class,
            'token' => \OranFry\Jars\Core\Linetypes\Token::class,
            'track' => Linetypes\Track::class,
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
            'artists' => Reports\Artists::class,
            'collection' => Reports\Collection::class,
            'downloads' => Reports\Downloads::class,
            'imagemetas' => Reports\ImageMetas::class,
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
