<?php

namespace Supplier\Traveline\Application\Dto\Reservation;

use Sdk\Module\Foundation\Support\Dto\Casts\CastInterface;
use Spatie\LaravelData\Support\DataProperty;

class MiddleNameCast implements CastInterface
{
    public function cast(DataProperty $property, mixed $value, array $context): mixed
    {
        $fullNameParts = explode(' ', $value);
        if (count($fullNameParts) === 3) {
            return $fullNameParts[2];
        }
        return null;
    }
}
