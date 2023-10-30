<?php

namespace Module\Integration\Traveline\Application\Dto\Reservation;

use Sdk\Module\Foundation\Support\Dto\Casts\CastInterface;
use Spatie\LaravelData\Support\DataProperty;

class FirstNameCast implements CastInterface
{
    public function cast(DataProperty $property, mixed $value, array $context): mixed
    {
        return explode(' ', $value)[0];
    }
}
