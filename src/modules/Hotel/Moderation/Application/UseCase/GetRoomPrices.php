<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\UseCase;

use Module\Hotel\Moderation\Application\Factory\PriceDtoFactory;
use Module\Hotel\Moderation\Infrastructure\Models\DatePrice;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetRoomPrices implements UseCaseInterface
{
    public function execute(int $seasonId): array
    {
        $prices = DatePrice::whereSeasonId($seasonId)->withGroup()->get();

        return PriceDtoFactory::collection($prices);
    }
}
