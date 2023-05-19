<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use App\Core\Support\Adapters\AbstractModuleAdapter;

class OrderAdapter extends AbstractModuleAdapter
{
    public function getActiveOrders(): array
    {
        return $this->request('getActiveOrders');
    }

    protected function getModuleKey(): string
    {
        return 'BookingOrder';
    }
}
