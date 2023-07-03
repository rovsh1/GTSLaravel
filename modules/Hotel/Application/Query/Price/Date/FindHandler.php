<?php

namespace Module\Hotel\Application\Query\Price\Date;

use Module\Hotel\Application\Response\PriceDto;
use Module\Hotel\Infrastructure\Models\DatePrice;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class FindHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface|Find $query): ?PriceDto
    {
        $datePrice = DatePrice::withGroup()
            ->whereSeasonId($query->seasonId)
            ->whereRoomId($query->roomId)
            ->whereDate('date', '=', $query->date)
            ->where('guests_count', '=', $query->guestsCount)
            ->where('is_resident', '=', $query->isResident)
            ->where('rate_id', '=', $query->rateId)
            ->first();
        if ($datePrice === null) {
            return null;
        }

        return new PriceDto(
            seasonId: $datePrice->season_id,
            price: $datePrice->price,
            rateId: $datePrice->rate_id,
            guestsCount: $datePrice->guests_count,
            roomId: $datePrice->room_id,
            isResident: $datePrice->is_resident,
            date: $datePrice->date,
        );
    }
}
