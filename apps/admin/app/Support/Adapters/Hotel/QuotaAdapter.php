<?php

namespace App\Admin\Support\Adapters\Hotel;

use App\Core\Support\Adapters\AbstractPortAdapter;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;

class QuotaAdapter extends AbstractPortAdapter
{
    protected string $module = 'hotel';

    public function getHotelQuotas(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        return $this->request('getHotelQuotas', [
            'hotel_id' => $hotelId,
            'room_id' => $roomId,
            'date_from' => $period->getStartDate(),
            'date_to' => $period->getEndDate(),
        ]);
    }

    public function updateRoomQuota(int $roomId, CarbonInterface $date, int $count): void
    {
        $this->request('updateRoomQuota', [
            'room_id' => $roomId,
            'date_from' => $date,
            'date_to' => $date->clone()->endOfDay(),
            'quota' => $count,
        ]);
    }

    public function openRoomQuota(int $roomId, CarbonInterface $date): void
    {
        $this->request('openRoomQuota', [
            'room_id' => $roomId,
            'date_from' => $date,
            'date_to' => $date->clone()->endOfDay(),
        ]);
    }

    public function closeRoomQuota(int $roomId, CarbonInterface $date): void
    {
        $this->request('closeRoomQuota', [
            'room_id' => $roomId,
            'date_from' => $date,
            'date_to' => $date->clone()->endOfDay(),
        ]);
    }
}
