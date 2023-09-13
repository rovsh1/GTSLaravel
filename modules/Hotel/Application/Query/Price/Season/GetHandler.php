<?php

namespace Module\Hotel\Application\Query\Price\Season;

use Module\Hotel\Application\Factory\PriceDtoFactory;
use Module\Hotel\Application\Factory\SeasonPriceDtoFactory;
use Module\Hotel\Infrastructure\Models\SeasonPrice;
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
