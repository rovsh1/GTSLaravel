<?php

namespace Module\Booking\Domain\ServiceBooking\ValueObject;

class CarBid
{
    public function __construct(
        private readonly CarId $carId,
        private readonly int $carsCount,
        private readonly int $passengersCount,
        private readonly int $baggageCount,
        private readonly int $babyCount,
    ) {}

    public function carId(): CarId
    {
        return $this->carId;
    }

    public function carsCount(): int
    {
        return $this->carsCount;
    }

    public function passengersCount(): int
    {
        return $this->passengersCount;
    }

    public function baggageCount(): int
    {
        return $this->baggageCount;
    }

    public function babyCount(): int
    {
        return $this->babyCount;
    }
}
