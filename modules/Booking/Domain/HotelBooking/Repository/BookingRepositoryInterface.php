<?php

namespace Module\Booking\Domain\HotelBooking\Repository;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Domain\HotelBooking\HotelBooking;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\BookingPeriod;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\HotelInfo;
use Module\Booking\Domain\Order\ValueObject\OrderId;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

interface BookingRepositoryInterface
{
    public function find(int $id): ?HotelBooking;

    /**
     * @param int $id
     * @return HotelBooking
     * @throws EntityNotFoundException
     */
    public function findOrFail(BookingId $id): HotelBooking;

    public function get(): Collection;

    public function create(
        OrderId $orderId,
        CreatorId $creatorId,
        BookingPeriod $period,
        ?string $note = null,
        HotelInfo $hotelInfo,
        CancelConditions $cancelConditions,
        BookingPrice $price,
        QuotaProcessingMethodEnum $quotaProcessingMethod,
    ): HotelBooking;

    public function store(HotelBooking $booking): bool;

    /**
     * @param CarbonInterface $dateUpdate
     * @param int|null $hotelId
     * @return BookingInterface[]
     */
    public function searchByDateUpdate(CarbonInterface $dateUpdate, ?int $hotelId): array;

    /**
     * @param int|null $hotelId
     * @return BookingInterface[]
     */
    public function searchActive(?int $hotelId): array;

    public function delete(BookingInterface|HotelBooking $booking): void;
}
