<?php

declare(strict_types=1);

namespace Sdk\Booking\ValueObject\CarBid;

use Sdk\Shared\Contracts\Support\CanEquate;
use Sdk\Shared\Contracts\Support\SerializableInterface;

class CarBidDetails implements CanEquate, SerializableInterface
{
    public function __construct(
        private readonly int $carsCount,
        private readonly int $passengersCount,
        private readonly int $baggageCount,
        private readonly int $babyCount,
    ) {}

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

    public function isEqual(mixed $b): bool
    {
        if (!$b instanceof CarBidDetails) {
            return false;
        }

        return $this->carsCount === $b->carsCount
            && $this->passengersCount === $b->passengersCount
            && $this->baggageCount === $b->baggageCount
            && $this->babyCount === $b->babyCount;
    }

    public function serialize(): array
    {
        return [
            'carsCount' => $this->carsCount,
            'passengersCount' => $this->passengersCount,
            'baggageCount' => $this->baggageCount,
            'babyCount' => $this->babyCount,
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            $payload['carsCount'],
            $payload['passengersCount'],
            $payload['baggageCount'],
            $payload['babyCount'],
        );
    }
}
