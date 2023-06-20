<?php

namespace Module\Booking\Hotel\Domain\Repository;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface as Base;
use Module\Booking\Hotel\Domain\Entity\Booking;
use Module\Booking\Hotel\Domain\ValueObject\Details\BookingPeriod;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelConditions;
use Module\Booking\Hotel\Domain\ValueObject\Details\HotelInfo;
use Module\Shared\Domain\ValueObject\Id;

interface BookingRepositoryInterface extends Base
{
    public function find(int $id): ?Booking;

    public function get(): Collection;

    public function create(
        Id $orderId,
        Id $creatorId,
        BookingPeriod $period,
        ?string $note = null,
        HotelInfo $hotelInfo,
        CancelConditions $cancelConditions
    ): Booking;

    public function store(Booking $booking): bool;

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
}
