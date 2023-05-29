<?php

namespace Module\Hotel\Application\Query\Price\Date;

use Illuminate\Database\Eloquent\Collection;
use Module\Hotel\Application\Dto\PriceDto;
use Module\Hotel\Infrastructure\Models\DatePrice;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class GetHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface|Get $query): mixed
    {
        $prices = DatePrice::whereSeasonId($query->seasonId)->withGroup()->get();

        return $this->buildDtos($prices);
    }

    private function buildDtos(Collection $prices): array
    {
        return $prices->map(fn(DatePrice $datePrice) => new PriceDto(
            seasonId: $datePrice->season_id,
            price: $datePrice->price,
            rateId: $datePrice->rate_id,
            guestsNumber: $datePrice->guests_number,
            roomId: $datePrice->room_id,
            currencyId: $datePrice->currency_id,
            isResident: $datePrice->is_resident,
            date: $datePrice->date,
        ))->all();
    }
}
