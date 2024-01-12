<?php

namespace Pkg\Supplier\Traveline\Dto\Reservation\Room;

use Custom\Framework\Foundation\Support\Dto\Casts\CastInterface;
use Spatie\LaravelData\Support\DataProperty;

class TotalCast implements CastInterface
{
    public function cast(DataProperty $property, mixed $value, array $context): mixed
    {
        return new TotalDto($value);
    }
}
