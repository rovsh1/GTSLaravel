<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking\Service;

use Illuminate\Support\Arr;
use Module\Booking\Application\Admin\ServiceBooking\Dto\CarBidDataDto;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\CarBid\Add;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\CarBid\Remove;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\CarBid\Update;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\Guest\Bind;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\Guest\Unbind;
use Module\Booking\Application\Admin\ServiceBooking\UseCase\UpdateDetailsField;

class DetailsAdapter
{
    public function updateDetailsField(int $bookingId, string $field, mixed $value): void
    {
        app(UpdateDetailsField::class)->execute($bookingId, $field, $value);
    }

    public function bindGuest(int $bookingId, int $guestId): void
    {
        app(Bind::class)->execute($bookingId, $guestId);
    }

    public function unbindGuest(int $bookingId, int $guestId): void
    {
        app(Unbind::class)->execute($bookingId, $guestId);
    }

    public function addCarBid(int $bookingId, array $carData): void
    {
        app(Add::class)->execute(
            $bookingId,
            $this->buildCarBidDto($carData)
        );
    }

    public function updateCarBid(int $bookingId, string $carBidId, array $carData): void
    {
        app(Update::class)->execute(
            $bookingId,
            $carBidId,
            $this->buildCarBidDto($carData)
        );
    }

    public function removeCarBid(int $bookingId, string $carBidId): void
    {
        app(Remove::class)->execute($bookingId, $carBidId);
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
