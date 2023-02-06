<?php

namespace GTS\Services\Integration\Traveline\Application\Dto;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

class OccupancyCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $context): mixed
    {
        $occupancies = [];
        for ($i = 1; $i <= $value; $i++) {
            $occupancies[] = [
                'id' => $context['id'],
                'personQuantity' => $i
            ];
        }
        return OccupancyDto::collection($occupancies);
    }
}
