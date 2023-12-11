<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Repository;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Booking\ValueObject\OrderId;

interface BookingRepositoryInterface
{
    public function add(Booking $booking): void;

    /**
     * @return Booking[]
     */
    public function get(): array;

    /**
     * @param OrderId $orderId
     * @return Booking[]
     */
    public function getByOrderId(OrderId $orderId): array;

    /**
     * @param GuestId $guestId
     * @return Booking[]
     */
    public function getByGuestId(GuestId $guestId): array;

    public function find(BookingId $id): ?Booking;

    public function findOrFail(BookingId $id): Booking;
}
