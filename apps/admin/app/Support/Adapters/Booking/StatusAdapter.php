<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use App\Core\Support\Adapters\AbstractModuleAdapter;
use Carbon\CarbonPeriod;

class StatusAdapter extends AbstractModuleAdapter
{
    public function getStatuses(): array
    {
        return $this->request('getStatuses');
    }

    public function getAvailableStatuses(int $id): array
    {
        return $this->request('getAvailableStatuses', ['id' => $id]);
    }

    protected function getModuleKey(): string
    {
        return 'CommonBooking';
    }
}
