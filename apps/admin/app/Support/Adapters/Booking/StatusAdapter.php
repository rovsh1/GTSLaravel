<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use App\Core\Support\Adapters\AbstractModuleAdapter;

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

    public function updateStatus(int $id, int $status): void
    {
        $this->request('updateStatus', ['id' => $id, 'status' => $status]);
    }

    protected function getModuleKey(): string
    {
        return 'CommonBooking';
    }
}
