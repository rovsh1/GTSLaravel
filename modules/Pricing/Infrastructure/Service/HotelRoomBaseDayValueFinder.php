<?php

declare(strict_types=1);

namespace Module\Pricing\Infrastructure\Service;

use DateTimeInterface;
use Module\Catalog\Infrastructure\Models\DatePrice;
use Module\Catalog\Infrastructure\Models\SeasonPrice;
use Module\Pricing\Domain\Hotel\Repository\HotelRepositoryInterface;
use Module\Pricing\Domain\Hotel\Service\BaseDayValueFinderInterface;
use Module\Pricing\Domain\Hotel\ValueObject\RoomId;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class HotelRoomBaseDayValueFinder implements BaseDayValueFinderInterface
{
    public function __construct(
        private readonly HotelRepositoryInterface $hotelRepository,
    ) {}

    public function find(
        RoomId $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        DateTimeInterface $date
    ): ?float {
        $hotel = $this->hotelRepository->findByRoomId($roomId);
        if (null === $hotel) {
            throw new EntityNotFoundException('Hotel not found');
        }

        $seasonId = $this->hotelRepository->findActiveSeasonId($hotel->id(), $date);
        if (null === $seasonId) {
            throw new EntityNotFoundException("Price season for hotel[{$hotel->id()}] undefined");
        }

        $dayPrice = $this->getDateValue(
            roomId: $roomId->value(),
            seasonId: $seasonId->value(),
            rateId: $rateId,
            isResident: $isResident,
            guestsCount: $guestsCount,
            date: $date
        );

        $seasonPrice = $this->getSeasonValue(
            roomId: $roomId->value(),
            seasonId: $seasonId->value(),
            rateId: $rateId,
            isResident: $isResident,
            guestsCount: $guestsCount,
        );
        if($seasonPrice === null){
            dd($seasonId, $rateId, $isResident, $guestsCount);
        }

        return $dayPrice ?? $seasonPrice;
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
            throw new EntityNotFoundException('Hotel room base day price not found');
        }

        return $value;
    }

    private function getDateValue(
        int $roomId,
        int $seasonId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        DateTimeInterface $date
    ): ?float {
        return DatePrice::withGroup()
            ->whereSeasonId($seasonId)
            ->whereRoomId($roomId)
            ->whereDate('date', '=', $date->format('Y-m-d'))
            ->where('guests_count', '=', $guestsCount)
            ->where('is_resident', '=', $isResident)
            ->where('rate_id', '=', $rateId)
            ->value('price');
    }

    private function getSeasonValue(
        int $roomId,
        int $seasonId,
        int $rateId,
        bool $isResident,
        int $guestsCount
    ): ?float {
        return SeasonPrice::withGroup()
            ->whereSeasonId($seasonId)
            ->whereRoomId($roomId)
            ->where('guests_count', '=', $guestsCount)
            ->where('is_resident', '=', $isResident)
            ->where('rate_id', '=', $rateId)
            ->value('price');
    }
}
