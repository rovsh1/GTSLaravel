<?php

namespace Supplier\Traveline\Application\Dto\Reservation;

use Sdk\Module\Foundation\Support\Dto\Casts\CastInterface;
use Spatie\LaravelData\Support\DataProperty;

class LastNameCast implements CastInterface
{
    public function cast(DataProperty $property, mixed $value, array $context): mixed
    {
        return explode(' ', $value)[1] ?? null;
    }
}
