<?php

namespace Module\Hotel\Application\Query\Price\Season;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Illuminate\Database\Eloquent\Collection;
use Module\Hotel\Application\Dto\PriceDto;
use Module\Hotel\Infrastructure\Models\SeasonPrice;

class GetHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface|Get $query): mixed
    {
        $prices = SeasonPrice::whereHotelId($query->hotelId)->withGroup()->get();

        return $this->buildDtos($prices);
    }

    private function buildDtos(Collection $prices): array
    {
        return $prices->map(fn(SeasonPrice $seasonPrice) => new PriceDto(
            seasonId: $seasonPrice->season_id,
            price: $seasonPrice->price,
            rateId: $seasonPrice->rate_id,
            guestsNumber: $seasonPrice->guests_number,
            roomId: $seasonPrice->room_id,
            currencyId: $seasonPrice->currency_id,
            isResident: $seasonPrice->is_resident
        ))->all();
    }
}
