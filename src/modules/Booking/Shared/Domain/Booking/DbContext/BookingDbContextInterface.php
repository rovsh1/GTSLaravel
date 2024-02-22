<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\DbContext;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\BookingIdCollection;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Booking\ValueObject\CancelConditions;
use Sdk\Booking\ValueObject\CreatorId;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Shared\Enum\ServiceTypeEnum;

interface BookingDbContextInterface
{
    public function create(
        OrderId $orderId,
        CreatorId $creatorId,
        BookingPrices $prices,
        ?CancelConditions $cancelConditions,
        ServiceTypeEnum $serviceType,
        ?string $note = null,
    ): Booking;

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

    /**
     * @param BookingIdCollection $ids
     * @return Booking[]
     */
    public function getBookings(BookingIdCollection $ids): array;

    public function find(BookingId $id): ?Booking;

    public function findOrFail(BookingId $id): Booking;

    public function store(Booking $booking): void;

    public function touch(BookingId $id): void;

    public function delete(Booking $booking): void;
}
