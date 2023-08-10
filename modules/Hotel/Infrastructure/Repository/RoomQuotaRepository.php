<?php

namespace Module\Hotel\Infrastructure\Repository;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Module\Hotel\Domain\Factory\RoomQuotaFactory;
use Module\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;
use Module\Hotel\Infrastructure\Models\Room\Quota as EloquentQuota;
use Module\Hotel\Infrastructure\Models\Room\QuotaStatusEnum;

class RoomQuotaRepository implements RoomQuotaRepositoryInterface
{
    public function get(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        $models = $this->getBaseQuotaQuery($hotelId, $period, $roomId)->get();

        return app(RoomQuotaFactory::class)->createCollectionFrom($models);
    }

    public function getAvailable(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        $models = $this->getBaseQuotaQuery($hotelId, $period, $roomId)
            ->whereAvailable()
            ->get();

        return app(RoomQuotaFactory::class)->createCollectionFrom($models);
    }

    public function getSold(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        $models = $this->getBaseQuotaQuery($hotelId, $period, $roomId)
            ->whereSold()
            ->get();

        return app(RoomQuotaFactory::class)->createCollectionFrom($models);
    }

    public function getStopped(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        $models = $this->getBaseQuotaQuery($hotelId, $period, $roomId)
            ->whereStopped()
            ->get();

        return app(RoomQuotaFactory::class)->createCollectionFrom($models);
    }

    public function updateRoomQuota(int $roomId, CarbonPeriod $period, ?int $quota, ?int $releaseDays = null): void
    {
        $updateData = ['date' => $period->getStartDate(), 'room_id' => $roomId, 'status' => QuotaStatusEnum::CLOSE];
        if ($quota !== null) {
            $updateData['count_total'] = $quota;
        }
        if ($releaseDays !== null) {
            $updateData['release_days'] = $releaseDays;
        }
        EloquentQuota::whereRoomId($roomId)
            ->wherePeriod($period)
            ->updateOrCreate(['date' => $period->getStartDate(), 'room_id' => $roomId], $updateData);
    }

    public function closeRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        EloquentQuota::whereRoomId($roomId)
            ->wherePeriod($period)
            ->update(['status' => QuotaStatusEnum::CLOSE]);
    }

    public function openRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        EloquentQuota::whereRoomId($roomId)
            ->wherePeriod($period)
            ->update(['status' => QuotaStatusEnum::OPEN]);
    }

    public function resetRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        EloquentQuota::whereRoomId($roomId)
            ->wherePeriod($period)
            ->delete();
    }

    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return EloquentQuota
     */
    private function getBaseQuotaQuery(int $hotelId, CarbonPeriod $period, ?int $roomId = null): Builder
    {
        return EloquentQuota::query()
            ->where(function (Builder $builder) use ($roomId, $hotelId) {
                if ($roomId !== null) {
                    $builder->whereRoomId($roomId);
                } else {
                    $builder->whereHotelId($hotelId);
                }
            })
            ->wherePeriod($period)
            ->addCountColumns();
    }
}
