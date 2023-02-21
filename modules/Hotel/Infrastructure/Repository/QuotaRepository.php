<?php

namespace Module\Hotel\Infrastructure\Repository;

use Carbon\CarbonPeriod;
use GTS\Hotel\Domain\Repository\QuotaRepositoryInterface;
use GTS\Hotel\Infrastructure\Repository\QuotaEvents;
use Module\Hotel\Infrastructure\Models\Room\Quota as EloquentQuota;

class QuotaRepository implements QuotaRepositoryInterface
{
    public function reserveRoomQuotaByDate(int $roomId, CarbonPeriod $period, int $quota): void
    {
        $existQuotas = EloquentQuota::whereRoomId($roomId)
            ->where('date', '>=', $period->getStartDate())
            ->where('date', '<', $period->getEndDate())
            ->get();

        if ($existQuotas->count() === 0) {
            //@todo какая-то ошибка, скорее всего неподключенный отель
            return;
        }

        foreach ($existQuotas as $existQuota) {
            try {
                $existQuota->count_available = $quota;
                $existQuota->saveOrFail();
//                $updateData = [
//                    'count_available' => $quota,
//                ];
//                EloquentQuota::whereRoomId($roomId)
//                    ->where('date', '=', $existQuota->date)
//                    ->update($updateData);
            } catch (\Throwable $e) {
                dd($e->getMessage());
                //@todo какое-то исключение
                return;
            }
        }
    }

    public function getAvailable(int $roomId, $date): int
    {
        return QuotaEvents::whereRoom($roomId)
            ->whereDate($date)
            ->sum('count');
    }

    public function getBooked(int $roomId, $date): int
    {
        return QuotaEvents::whereRoom($roomId)
            ->whereDate($date)
            ->sum('count');
    }
}
