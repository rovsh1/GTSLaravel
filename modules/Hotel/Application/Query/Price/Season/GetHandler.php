<?php

namespace Module\Hotel\Application\Query\Price\Season;

use Illuminate\Database\Eloquent\Collection;
use Module\Hotel\Application\Dto\PriceDto;
use Module\Hotel\Application\Factory\PriceDtoFactory;
use Module\Hotel\Infrastructure\Models\SeasonPrice;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class GetHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface|Get $query): mixed
    {
        $prices = SeasonPrice::whereHotelId($query->hotelId)->withGroup()->get();

        return PriceDtoFactory::collection($prices);
    }
}
