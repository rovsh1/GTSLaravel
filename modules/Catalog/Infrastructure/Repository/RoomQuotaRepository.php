<?php

namespace Module\Catalog\Infrastructure\Repository;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Module\Catalog\Domain\Hotel\Factory\RoomQuotaFactory;
use Module\Catalog\Domain\Hotel\Repository\RoomQuotaRepositoryInterface;
use Module\Catalog\Infrastructure\Models\Room\Quota as EloquentQuota;
use Module\Catalog\Infrastructure\Models\Room\QuotaStatusEnum;

class RoomQuotaRepository implements RoomQuotaRepositoryInterface
{
    public function __construct(
        private readonly RoomQuotaFactory $factory
    ) {}

    public function get(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        $models = $this->getBaseQuotaQuery($hotelId, $period, $roomId)->get();

        return $this->factory->createCollectionFrom($models);
    }

    public function getAvailable(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        $models = $this->getBaseQuotaQuery($hotelId, $period, $roomId)
            ->whereAvailable()
            ->get();

        return $this->factory->createCollectionFrom($models);
    }

    public function getSold(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        $models = $this->getBaseQuotaQuery($hotelId, $period, $roomId)
            ->whereSold()
            ->get();

        return $this->factory->createCollectionFrom($models);
    }

    public function getStopped(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        $models = $this->getBaseQuotaQuery($hotelId, $period, $roomId)
            ->whereStopped()
            ->get();

        return $this->factory->createCollectionFrom($models);
    }

    public function updateRoomQuota(int $roomId, CarbonPeriod $period, ?int $quota, ?int $releaseDays = null): void
    {
        $updateData = ['date' => $period->getStartDate(), 'room_id' => $roomId];
        if ($quota !== null) {
            $updateData['count_total'] = $quota;
        }
        if ($releaseDays !== null) {
            $updateData['release_days'] = $releaseDays;
        }
        EloquentQuota::whereRoomId($roomId)
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
            ->withCountColumns();
    }
}
