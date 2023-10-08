<?php

declare(strict_types=1);

namespace Module\Booking\Domain\HotelBooking\Repository;

use Module\Booking\Domain\HotelBooking\Entity\RoomQuota;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\BookingPeriod;
use Module\Booking\Domain\HotelBooking\ValueObject\QuotaId;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;

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
