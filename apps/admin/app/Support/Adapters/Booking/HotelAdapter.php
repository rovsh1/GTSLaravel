<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use App\Core\Support\Adapters\AbstractModuleAdapter;
use Carbon\CarbonPeriod;

class HotelAdapter extends AbstractModuleAdapter
{
    public function getBookings(array $filters = []): mixed
    {
        return $this->request('getBookings', []);
    }

    public function getBooking(int $id): mixed
    {
        return $this->request('getBooking', ['id' => $id]);
    }

    public function createBooking(
        int $cityId,
        int $clientId,
        int $hotelId,
        CarbonPeriod $period,
        ?int $orderId,
        ?string $note = null
    ): int {
        return $this->request('createBooking', [
            'cityId' => $cityId,
            'clientId' => $clientId,
            'hotelId' => $hotelId,
            'dateStart' => $period->getStartDate(),
            'dateEnd' => $period->getEndDate(),
            'orderId' => $orderId,
            'note' => $note,
        ]);
    }

    protected function getModuleKey(): string
    {
        return 'HotelBooking';
    }
}
