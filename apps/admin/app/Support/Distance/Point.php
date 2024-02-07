<?php

namespace App\Admin\Support\Distance;

use App\Admin\Exceptions\InvalidPointCoordinates;

final class Point
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude
    ) {
    }

    public static function buildFromCoordinates(string $coordinates): self
    {
        $coordinatesParts = explode(',', $coordinates);
        if (count($coordinatesParts) !== 2) {
            throw new InvalidPointCoordinates();
        }
        [$latitude, $longitude] = $coordinatesParts;
        if (!is_numeric($latitude) || !is_numeric($longitude)) {
            throw new InvalidPointCoordinates();
        }

        return new self(trim($latitude), trim($longitude));
    }
}
