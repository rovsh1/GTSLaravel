<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Repository;

use Carbon\CarbonPeriod;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Entity\RoomQuota;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\BookingPeriod;
use Module\Booking\HotelBooking\Domain\ValueObject\QuotaId;
use Module\Shared\Contracts\Repository\CanTransactionInterface;

interface BookingQuotaRepositoryInterface extends CanTransactionInterface
{
    /**
     * @param BookingPeriod $period
     * @param int[] $roomIds
     * @return array<int, RoomQuota>
     */
    public function getAvailableQuotas(BookingPeriod $period, array $roomIds): array;

    public function book(BookingId $id, QuotaId $quotaId, int $count, array $context): void;

    public function reserve(BookingId $id, QuotaId $quotaId, int $count, array $context): void;

    public function resetByBookingId(BookingId $id): void;
}
