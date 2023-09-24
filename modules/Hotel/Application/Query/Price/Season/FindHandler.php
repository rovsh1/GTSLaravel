<?php

namespace Module\Hotel\Application\Query\Price\Season;

use Module\Hotel\Application\Response\PriceDto;
use Module\Hotel\Infrastructure\Models\SeasonPrice;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class FindHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface|Find $query): ?PriceDto
    {
        $seasonPrice = SeasonPrice::withGroup()
            ->whereSeasonId($query->seasonId)
            ->whereRoomId($query->roomId)
            ->where('guests_count', '=', $query->guestsCount)
            ->where('is_resident', '=', $query->isResident)
            ->where('rate_id', '=', $query->rateId)
            ->first();
        if ($seasonPrice === null) {
            return null;
        }

        return new PriceDto(
            seasonId: $seasonPrice->season_id,
            price: $seasonPrice->price,
            rateId: $seasonPrice->rate_id,
            guestsCount: $seasonPrice->guests_count,
            roomId: $seasonPrice->room_id,
            isResident: $seasonPrice->is_resident,
        );
    }
}
