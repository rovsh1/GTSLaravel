<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Infrastructure\Adapter;

use App\Core\Support\Adapters\AbstractModuleAdapter;
use Module\Booking\Hotel\Domain\Adapter\OrderAdapterInterface;

class OrderAdapter extends AbstractModuleAdapter implements OrderAdapterInterface
{
    public function createOrder(int $clientId): int
    {
        return $this->request('createOrder', ['clientId' => $clientId]);
    }

    protected function getModuleKey(): string
    {
        return 'BookingOrder';
    }
}
