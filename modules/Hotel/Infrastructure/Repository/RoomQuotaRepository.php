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
        $models = EloquentQuota::query()
            ->where(function (Builder $builder) use ($roomId, $hotelId) {
                if ($roomId !== null) {
                    $builder->whereRoomId($roomId);
                } else {
                    $builder->whereHas('room', function (Builder $query) use ($hotelId) {
                        $query->whereHotelId($hotelId);
                    });
                }
            })
            ->where('date', '>=', $period->getStartDate())
            ->where('date', '<', $period->getEndDate())
            ->get();

        return app(RoomQuotaFactory::class)->createCollectionFrom($models);
    }

    public function updateRoomQuota(int $roomId, CarbonPeriod $period, ?int $quota, ?int $releaseDays = null): void
    {
        $updateData = ['date' => $period->getStartDate(), 'room_id' => $roomId, 'status' => QuotaStatusEnum::Close];
        if ($quota !== null) {
            $updateData['total_count'] = $quota;
        }
        if ($releaseDays !== null) {
            $updateData['release_days'] = $releaseDays;
        }
        EloquentQuota::whereRoomId($roomId)
            ->where('date', '>=', $period->getStartDate())
            ->where('date', '<', $period->getEndDate())
            ->updateOrCreate(['date' => $period->getStartDate(), 'room_id' => $roomId], $updateData);
    }

    public function closeRoomQuota(int $roomId, CarbonPeriod $period)
    {
        EloquentQuota::whereRoomId($roomId)
            ->where('date', '>=', $period->getStartDate())
            ->where('date', '<', $period->getEndDate())
            ->update(['status' => QuotaStatusEnum::Close]);
    }

    public function openRoomQuota(int $roomId, CarbonPeriod $period)
    {
        EloquentQuota::whereRoomId($roomId)
            ->where('date', '>=', $period->getStartDate())
            ->where('date', '<', $period->getEndDate())
            ->update(['status' => QuotaStatusEnum::Open]);
    }
}
