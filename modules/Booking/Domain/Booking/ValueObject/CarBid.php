<?php

namespace Module\Booking\Domain\Booking\ValueObject;

use Illuminate\Support\Str;
use Module\Booking\Domain\Booking\ValueObject\CarBid\CarBidPrices;
use Module\Shared\Contracts\Support\SerializableDataInterface;

final class CarBid implements SerializableDataInterface
{
    public function __construct(
        private readonly string $id,
        private readonly CarId $carId,
        private readonly int $carsCount,
        private readonly int $passengersCount,
        private readonly int $baggageCount,
        private readonly int $babyCount,
        private readonly CarBidPrices $prices,
    ) {}

    public static function create(
        CarId $carId,
        int $carsCount,
        int $passengersCount,
        int $baggageCount,
        int $babyCount,
        CarBidPrices $prices,
    ): static {
        return new static(
            Str::random(6),
            $carId,
            $carsCount,
            $passengersCount,
            $baggageCount,
            $babyCount,
            $prices
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

    public function prices(): CarBidPrices
    {
        return $this->prices;
    }

    public function toData(): array
    {
        return [
            'id' => $this->id,
            'carId' => $this->carId->value(),
            'carsCount' => $this->carsCount,
            'passengersCount' => $this->passengersCount,
            'baggageCount' => $this->baggageCount,
            'babyCount' => $this->babyCount,
            'prices' => $this->prices->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            $data['id'],
            new CarId($data['carId']),
            $data['carsCount'],
            $data['passengersCount'],
            $data['baggageCount'],
            $data['babyCount'],
            CarBidPrices::fromData($data['prices'])
        );
    }
}
