<?php

namespace Module\Booking\Hotel\Domain\ValueObject;

final class RoomPrice
{
    public function __construct(
        private readonly float $netValue,
        private readonly float $avgDailyValue,
        private readonly float $hotelValue,
        private readonly float $clientValue
    ) {
    }

    public function netValue(): float
    {
        return $this->netValue;
    }

    public function avgDailyValue(): float
    {
        return $this->avgDailyValue;
    }

    public function hotelValue(): float
    {
        return $this->hotelValue;
    }

    public function clientValue(): float
    {
        return $this->clientValue;
    }

    public function serialize(): array
    {
        return [
            'netValue' => $this->netValue,
            'avgDailyValue' => $this->avgDailyValue,
            'hotelValue' => $this->hotelValue,
            'clientValue' => $this->clientValue,
        ];
    }

    public static function deserialize(array $payload): RoomPrice
    {
        return new RoomPrice(
            $payload['netValue'],
            $payload['avgDailyValue'],
            $payload['hotelValue'],
            $payload['clientValue'],
        );
    }
}
