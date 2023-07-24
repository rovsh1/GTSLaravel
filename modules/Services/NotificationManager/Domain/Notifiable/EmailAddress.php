<?php

namespace Module\Services\NotificationManager\Domain\Notifiable;

use Module\Services\NotificationManager\Domain\Entity\NotifiableInterface;
use Sdk\Module\Foundation\Exception\ValidationException;

class EmailAddress implements NotifiableInterface
{
    private string $email;

    private ?string $name;

    public function __construct(string $email, string $name = null)
    {
        $this->setEmail($email);
        //$this->setName($name);
    }

    public function setEmail(string $email)
    {
        $this->email = $this->validateEmail($email);
    }

    public function email(): string
    {
        return $this->email;
    }

    private function validateEmail(string $email): string
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('Email validation failed');
        }

        return $email;
    }
}