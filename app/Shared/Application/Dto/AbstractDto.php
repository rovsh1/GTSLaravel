<?php

namespace GTS\Shared\Application\Dto;

use GTS\Shared\Infrastructure\Facade\DomainModelNormalizer;

use Spatie\LaravelData\Data;

class AbstractDto extends Data
{
    public static function normalizers(): array
    {
        return array_merge(parent::normalizers(), [
                DomainModelNormalizer::class
            ]
        );
    }

}
