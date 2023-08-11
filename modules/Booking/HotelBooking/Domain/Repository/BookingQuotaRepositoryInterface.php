<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Repository;

use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Entity\RoomQuota;
use Module\Shared\Contracts\Repository\CanTransactionInterface;

interface BookingQuotaRepositoryInterface extends CanTransactionInterface
{
    /**
     * @param Booking $booking
     * @param CarbonPeriod $period
     * @return array<int, RoomQuota>
     */
    public function getAvailableQuotas(Booking $booking): array;

    public function book(BookingId $id, int $roomId, CarbonImmutable $date, int $count, array $context): void;

    public function reserve(BookingId $id, int $roomId, CarbonImmutable $date, int $count, array $context): void;

    public function resetByBookingId(BookingId $id): void;
}
