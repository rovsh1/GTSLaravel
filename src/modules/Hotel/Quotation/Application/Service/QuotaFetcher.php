<?php

namespace Module\Hotel\Quotation\Application\Service;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Module\Hotel\Quotation\Application\Dto\QuotaDto;
use Module\Hotel\Quotation\Infrastructure\Model\Quota;
use Module\Shared\Enum\Hotel\QuotaStatusEnum;

class QuotaFetcher
{
    public function getAll(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->mapQuery(
            $this->bootQuery($hotelId, $period, $roomId)
        );
    }

    public function getAvailable(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->mapQuery(
            $this->bootQuery($hotelId, $period, $roomId)
                ->whereOpened()
                ->whereHasAvailable()
        );
    }

    public function getClosed(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->mapQuery(
            $this->bootQuery($hotelId, $period, $roomId)
                ->whereClosed()
        );
    }

    public function getSold(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->mapQuery(
            $this->bootQuery($hotelId, $period, $roomId)
                ->whereSold()
        );
    }

    private function bootQuery(int $hotelId, CarbonPeriod $period, ?int $roomId): Builder
    {
        return Quota::query()
            ->whereHotelId($hotelId, $roomId)
            ->wherePeriod($period)
            ->withCountColumns();
    }

    /**
     * @param $query
     * @return QuotaDto[]
     */
    private function mapQuery($query): array
    {
        return $query
            ->get()
            ->map(fn(Quota $quota) => new QuotaDto(
                id: $quota->id,
                roomId: $quota->room_id,
                date: new Carbon($quota->date),
                status: $quota->status === QuotaStatusEnum::OPEN,
                releaseDays: $quota->release_days,
                countTotal: $quota->count_total,
                countAvailable: $quota->count_available,
                countBooked: $quota->count_booked,
                countReserved: $quota->count_reserved
            ))
            ->all();
    }
}
