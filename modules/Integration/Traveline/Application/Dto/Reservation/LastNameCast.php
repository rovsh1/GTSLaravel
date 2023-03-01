<?php

namespace Module\Integration\Traveline\Application\Dto\Reservation;

use Custom\Framework\Foundation\Support\Dto\Casts\CastInterface;
use Spatie\LaravelData\Support\DataProperty;

class LastNameCast implements CastInterface
{
    public function cast(DataProperty $property, mixed $value, array $context): mixed
    {
        return explode(' ', $value)[1] ?? null;
    }
}
