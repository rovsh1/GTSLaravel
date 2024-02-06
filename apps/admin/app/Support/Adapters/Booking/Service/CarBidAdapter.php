<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking\Service;

use Illuminate\Support\Arr;
use Module\Booking\Moderation\Application\Dto\CarBidDataDto;
use Module\Booking\Moderation\Application\UseCase\ServiceBooking\CarBid\Add;
use Module\Booking\Moderation\Application\UseCase\ServiceBooking\CarBid\Guest;
use Module\Booking\Moderation\Application\UseCase\ServiceBooking\CarBid\Remove;
use Module\Booking\Moderation\Application\UseCase\ServiceBooking\CarBid\SetManualPrice;
use Module\Booking\Moderation\Application\UseCase\ServiceBooking\CarBid\Update;

class CarBidAdapter
{
    public function bindGuest(int $bookingId, int $carBidId, int $guestId): void
    {
        app(Guest\Bind::class)->execute($bookingId, $carBidId, $guestId);
    }

    public function unbindGuest(int $bookingId, int $carBidId, int $guestId): void
    {
        app(Guest\Unbind::class)->execute($bookingId, $carBidId, $guestId);
    }

    public function addCarBid(int $bookingId, array $carData): void
    {
        app(Add::class)->execute(
            $bookingId,
            $this->buildCarBidDto($carData)
        );
    }

    public function updateCarBid(int $bookingId, int $carBidId, array $carData): void
    {
        app(Update::class)->execute(
            $bookingId,
            $carBidId,
            $this->buildCarBidDto($carData)
        );
    }

    public function removeCarBid(int $bookingId, int $carBidId): void
    {
        app(Remove::class)->execute($bookingId, $carBidId);
    }

    public function setManualPrice(
        int $bookingId,
        int $carBidId,
        ?float $clientPerCarPrice,
        ?float $supplierPerCarPrice = null
    ): void {
        app(SetManualPrice::class)->execute($bookingId, $carBidId, $clientPerCarPrice, $supplierPerCarPrice);
    }

    private function buildCarBidDto(array $data): CarBidDataDto
    {
        if (!Arr::has($data, ['carId', 'carsCount', 'passengersCount', 'baggageCount', 'babyCount'])) {
            throw new \InvalidArgumentException('Invalid car bid');
        }

        return new CarBidDataDto(
            $data['carId'],
            $data['carsCount'],
            $data['passengersCount'],
            $data['baggageCount'],
            $data['babyCount'],
        );
    }
}
