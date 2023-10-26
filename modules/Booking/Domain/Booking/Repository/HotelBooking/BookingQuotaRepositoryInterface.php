<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Repository\HotelBooking;

use Module\Booking\Domain\Booking\Entity\HotelBooking\RoomQuota;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\BookingPeriod;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\QuotaId;

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
