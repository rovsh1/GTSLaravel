<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Factory;

use Illuminate\Support\Enumerable;
use Module\Hotel\Moderation\Application\Dto\SeasonPriceDto;

class SeasonPriceDtoFactory
{
    public static function from(mixed $price): SeasonPriceDto
    {
        return new SeasonPriceDto(
            seasonId: $price->season_id,
            price: (float)$price->price,
            rateId: $price->rate_id,
            guestsCount: $price->guests_count,
            roomId: $price->room_id,
            isResident: (bool)$price->is_resident,
            hasDatePrices: $price->has_date_prices
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
