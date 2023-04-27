<?php

namespace App\Admin\Support\Adapters\Hotel;

use App\Core\Support\Adapters\AbstractPortAdapter;
use Carbon\CarbonPeriod;

class QuotaAdapter extends AbstractPortAdapter
{
    protected string $module = 'hotel';

    public function getRoomQuota(int $roomId, CarbonPeriod $period): array
    {
        return $this->request('getRoomQuota', [
            'room_id' => $roomId,
            'date_from' => $period->getStartDate(),
            'date_to' => $period->getEndDate(),
        ]);
    }
}
