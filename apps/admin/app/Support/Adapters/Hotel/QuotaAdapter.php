<?php

namespace App\Admin\Support\Adapters\Hotel;

use App\Core\Support\Adapters\AbstractHotelAdapter;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;

class QuotaAdapter extends AbstractHotelAdapter
{
    public function getQuotas(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        return $this->request('getQuotas', [
            'hotel_id' => $hotelId,
            'room_id' => $roomId,
            'date_from' => $period->getStartDate(),
            'date_to' => $period->getEndDate(),
        ]);
    }

    public function getAvailableQuotas(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        return $this->request('getAvailableQuotas', [
            'hotel_id' => $hotelId,
            'room_id' => $roomId,
            'date_from' => $period->getStartDate(),
            'date_to' => $period->getEndDate(),
        ]);
    }

    public function getSoldQuotas(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        return $this->request('getSoldQuotas', [
            'hotel_id' => $hotelId,
            'room_id' => $roomId,
            'date_from' => $period->getStartDate(),
            'date_to' => $period->getEndDate(),
        ]);
    }

    public function getStoppedQuotas(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        return $this->request('getStoppedQuotas', [
            'hotel_id' => $hotelId,
            'room_id' => $roomId,
            'date_from' => $period->getStartDate(),
            'date_to' => $period->getEndDate(),
        ]);
    }

    public function updateRoomQuota(int $roomId, CarbonInterface $date, ?int $count, ?int $releaseDays = null): void
    {
        $this->request('updateRoomQuota', [
            'room_id' => $roomId,
            'quota' => $count,
            'release_days' => $releaseDays,
            ...$this->getPeriodByDate($date)
        ]);
    }

    public function openRoomQuota(int $roomId, CarbonInterface $date): void
    {
        $this->request('openRoomQuota', [
            'room_id' => $roomId,
            ...$this->getPeriodByDate($date)
        ]);
    }

    public function closeRoomQuota(int $roomId, CarbonInterface $date): void
    {
        $this->request('closeRoomQuota', [
            'room_id' => $roomId,
            ...$this->getPeriodByDate($date)
        ]);
    }

    public function resetRoomQuota(int $roomId, CarbonInterface $date): void
    {
        $this->request('resetRoomQuota', [
            'room_id' => $roomId,
            ...$this->getPeriodByDate($date)
        ]);
    }

    /**
     * @param CarbonInterface $date
     * @return array<string, CarbonInterface>
     */
    private function getPeriodByDate(CarbonInterface $date): array
    {
        return [
            'date_from' => $date->clone()->startOfDay(),
            'date_to' => $date->clone()->endOfDay(),
        ];
    }
}
