<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\Hotel\Service;

use DateTimeInterface;
use Module\Pricing\Domain\Hotel\Repository\HotelRepositoryInterface;
use Module\Pricing\Domain\Hotel\ValueObject\RoomId;
use Module\Shared\Enum\CurrencyEnum;

class BaseDayValueFinder
{
    public function __construct(
        private readonly HotelRepositoryInterface $hotelRepository,
        private readonly RoomPriceFinderInterface $roomPriceFinder,
    ) {
    }

    public function find(
        RoomId $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        DateTimeInterface $date
    ): ?float {
        $hotel = $this->hotelRepository->findByRoomId($roomId);
        if (null === $hotel) {
            throw new \Exception();
        }

        $seasonId = $this->hotelRepository->findActiveSeasonId($hotel->id(), $date);
        if (null === $seasonId) {
            throw new \Exception("Price season for hotel[{$hotel->id()}] undefined");
        }

        return $this->roomPriceFinder->findForDate(
            roomId: $roomId,
            seasonId: $seasonId,
            rateId: $rateId,
            isResident: $isResident,
            guestsCount: $guestsCount,
            date: $date
        );
    }

    public function findOrFail(
        RoomId $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        DateTimeInterface $date
    ): float {
        $value = $this->find($roomId, $rateId, $isResident, $guestsCount, $date);
        if (null === $value) {
            throw new \Exception();
        }

        return $value;
    }
}
