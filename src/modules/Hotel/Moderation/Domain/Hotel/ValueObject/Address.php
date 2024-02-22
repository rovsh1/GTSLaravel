<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Domain\Hotel\ValueObject;

class Address
{
    public function __construct(
        private readonly string $country,
        private readonly string $city,
        private readonly string $address,
    ) {}

    public function country(): string
    {
        return $this->country;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function address(): string
    {
        return $this->address;
    }
}
