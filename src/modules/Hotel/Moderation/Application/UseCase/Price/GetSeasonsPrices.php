<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\UseCase\Price;

use Module\Hotel\Moderation\Application\Factory\SeasonPriceDtoFactory;
use Module\Hotel\Moderation\Infrastructure\Models\SeasonPrice;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetSeasonsPrices implements UseCaseInterface
{
    public function execute(int $hotelId): array
    {
        $prices = SeasonPrice::whereHotelId($hotelId)->withGroup()->get();

        return SeasonPriceDtoFactory::collection($prices);
    }
}
