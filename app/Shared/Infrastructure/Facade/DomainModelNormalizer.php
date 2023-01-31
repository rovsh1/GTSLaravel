<?php

namespace GTS\Shared\Infrastructure\Facade;

use Spatie\LaravelData\Normalizers\Normalizer;

class DomainModelNormalizer implements Normalizer
{

    public function normalize(mixed $value): ?array
    {
        if (!is_object($value)) {
            return null;
        }
        return get_object_vars($value);
    }
}
