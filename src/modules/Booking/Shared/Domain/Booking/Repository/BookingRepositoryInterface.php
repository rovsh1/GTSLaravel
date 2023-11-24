<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Repository;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Shared\Domain\Shared\ValueObject\CreatorId;
use Module\Shared\Enum\Booking\BookingStatusEnum;
use Module\Shared\Enum\ServiceTypeEnum;

interface BookingRepositoryInterface
{
    public function add(Booking $booking): void;

    /**
     * @return Booking[]
     */
    public function get(): array;

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

    public function find(BookingId $id): ?Booking;

    public function findOrFail(BookingId $id): Booking;

    public function store(Booking $booking): void;

    public function delete(Booking $booking): void;
}
