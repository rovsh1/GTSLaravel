<?php

namespace Module\Shared\Domain\ValueObject;

use Custom\Framework\Foundation\Exception\ValidationException;

class Phone
{
    private ?string $country;

    private ?string $number;

    public function __construct(?string $country = null, ?string $number = null)
    {
        if ($country && !$number)
            throw new \ArgumentCountError('Phone number required');

        $this->setCountry($country);
        $this->setNumber($number);
    }

    public function country(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->validateCountry($country);

        $this->country = $country;
    }

    public function number(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): void
    {
        $this->validateNumber($number);

        $this->number = $number;
    }

    public function reset(): void
    {
        $this->country = null;
        $this->number = null;
    }

    private function validateCountry(string $country): void
    {
        if (!preg_match('/^\w{2}$/', $country))
            throw new ValidationException('Phone country validation failed');
    }

    private function validateNumber(string $number): void
    {
        if (!preg_match('/^\d+$/', $number))
            throw new ValidationException('Phone number validation failed');
    }
}
