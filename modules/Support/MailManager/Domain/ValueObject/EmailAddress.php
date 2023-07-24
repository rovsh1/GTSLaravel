<?php

namespace Module\Support\MailManager\Domain\ValueObject;

class EmailAddress
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $this->validateEmail($email);
    }

    public function value(): string
    {
        return $this->email;
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