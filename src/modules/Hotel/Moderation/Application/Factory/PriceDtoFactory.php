<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Factory;

use Illuminate\Support\Enumerable;
use Module\Hotel\Moderation\Application\Dto\PriceDto;

class PriceDtoFactory
{
    public static function from(mixed $price): PriceDto
    {
        return new PriceDto(
            seasonId: $price->season_id,
            roomId: $price->room_id,
            rateId: $price->rate_id,
            guestsCount: $price->guests_count,
            isResident: (bool)$price->is_resident,
            price: (float)$price->price,
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
