<?php

declare(strict_types=1);

namespace Module\Booking\Deprecated\HotelBooking\Repository;

use Module\Booking\Deprecated\HotelBooking\Entity\RoomQuota;
use Module\Booking\Deprecated\HotelBooking\ValueObject\QuotaId;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\BookingPeriod;

interface BookingQuotaRepositoryInterface
{
    /**
     * @param BookingPeriod $period
     * @param int[] $roomIds
     * @return array<int, RoomQuota>
     */
    public function getAvailableQuotas(BookingPeriod $period, array $roomIds): array;

    public function book(BookingId $id, QuotaId $quotaId, int $count, array $context): void;

    public function reserve(BookingId $id, QuotaId $quotaId, int $count, array $context): void;

    public function cancel(BookingId $id): void;
}
