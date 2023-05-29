<?php

namespace Module\Integration\Traveline\Application\Dto\Reservation\Room;

use Sdk\Module\Foundation\Support\Dto\Casts\CastInterface;
use Spatie\LaravelData\Support\DataProperty;

class TotalCast implements CastInterface
{
    public function cast(DataProperty $property, mixed $value, array $context): mixed
    {
        return new TotalDto($value);
    }
}
