<?php

namespace Module\Hotel\Moderation\Application\Admin\Query\Price\Season;

use Module\Hotel\Moderation\Application\Admin\Factory\SeasonPriceDtoFactory;
use Module\Hotel\Moderation\Infrastructure\Models\SeasonPrice;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class GetHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface|Get $query): mixed
    {
        $prices = SeasonPrice::whereHotelId($query->hotelId)->withGroup()->get();

        return SeasonPriceDtoFactory::collection($prices);
    }
}
