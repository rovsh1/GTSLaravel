<?php

declare(strict_types=1);

namespace Module\Hotel\Application\Factory;

use Illuminate\Support\Enumerable;
use Module\Hotel\Application\Response\PriceDto;

class PriceDtoFactory
{
    public static function from(mixed $price): PriceDto
    {
        return new PriceDto(
            seasonId: $price->season_id,
            price: (float)$price->price,
            rateId: $price->rate_id,
            guestsCount: $price->guests_count,
            roomId: $price->room_id,
            isResident: (bool)$price->is_resident,
            date: $price?->date
        );
    }

    public static function collection(array|Enumerable $items): array
    {
        if ($items instanceof Enumerable) {
            return $items->map(fn($item) => self::from($item))->all();
        }

        return array_map(fn($item) => self::from($item), $items);
    }
}
