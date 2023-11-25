<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Repository;

use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Booking\ValueObject\CancelConditions;
use Sdk\Booking\ValueObject\CreatorId;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Shared\Enum\ServiceTypeEnum;

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
