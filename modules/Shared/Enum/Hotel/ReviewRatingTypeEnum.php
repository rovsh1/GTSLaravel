<?php

declare(strict_types=1);

namespace Module\Shared\Enum\Hotel;

enum ReviewRatingTypeEnum: int
{
    case STAFF = 1;
    case FACILITIES = 2;
    case CLEANNESS = 3;
    case COMFORT = 4;
    case PRICE_QUALITY = 5;
    case LOCATION = 6;
    case WIFI = 7;

    public function calculateValue(float|int $rating)
    {
        $ratio = match ($this) {
            self::STAFF => 0.1,
            self::FACILITIES,
            self::COMFORT,
            self::PRICE_QUALITY => 0.15,
            self::CLEANNESS,
            self::LOCATION => 0.2,
            self::WIFI => 0.05,
        };

        return $rating * $ratio;
    }
}
