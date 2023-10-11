<?php

namespace Module\Booking\Domain\Booking\ValueObject;

use Illuminate\Support\Str;

final class CarBid
{
    public function __construct(
        private readonly string $id,
        private readonly CarId $carId,
        private readonly int $carsCount,
        private readonly int $passengersCount,
        private readonly int $baggageCount,
        private readonly int $babyCount,
    ) {}

    public static function create(
        CarId $carId,
        int $carsCount,
        int $passengersCount,
        int $baggageCount,
        int $babyCount
    ): static {
        return new static(
            Str::random(6),
            $carId,
            $carsCount,
            $passengersCount,
            $baggageCount,
            $babyCount
        );
    }

    public function id(): string
    {
        return $this->id;
    }

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
