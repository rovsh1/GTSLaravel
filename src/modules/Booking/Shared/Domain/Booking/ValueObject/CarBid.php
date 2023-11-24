<?php

namespace Module\Booking\Shared\Domain\Booking\ValueObject;

use Illuminate\Support\Str;
use Module\Booking\Shared\Domain\Booking\ValueObject\CarBid\CarBidPrices;
use Module\Shared\Contracts\Support\SerializableInterface;

final class CarBid implements SerializableInterface
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

    public function supplierPriceValue(): float
    {
        return $this->prices->supplierPrice()->valuePerCar() * $this->carsCount;
    }

    public function clientPriceValue(): float
    {
        return $this->prices->clientPrice()->valuePerCar() * $this->carsCount;
    }

    public function serialize(): array
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

    public static function deserialize(array $payload): static
    {
        return new static(
            $payload['id'],
            new CarId($payload['carId']),
            $payload['carsCount'],
            $payload['passengersCount'],
            $payload['baggageCount'],
            $payload['babyCount'],
            CarBidPrices::fromData($payload['prices'])
        );
    }
}
