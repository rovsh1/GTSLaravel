<?php

namespace App\Admin\Support\Distance;

final class Point
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude
    ) {}

    public static function buildFromCoordinates(string $coordinates): self
    {
        [$latitude, $longitude] = explode(',', $coordinates);
        return new self(trim($latitude), trim($longitude));
    }
}
