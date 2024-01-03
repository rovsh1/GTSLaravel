<?php

declare(strict_types=1);

namespace Support\MailManager\ValueObject;

final class EmailAddress
{
    private string $email;

    private ?string $name;

    public function __construct(string $email, ?string $name = null)
    {
        $this->email = $this->validateEmail($email);
        $this->name = $name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->email;
    }

    private function validateEmail(string $address): string
    {
        $address = trim($address);
        if (!filter_var($address, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Email address [' . $address . '] invalid');
        }

        return $address;
    }
}