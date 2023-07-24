<?php

declare(strict_types=1);

namespace Module\Administrator\Domain\Entity;

use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Id;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Administrator extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly Id $id,
        private string $presentation,
        private ?string $email,
        private ?string $phone,
        private ?string $name,
        private ?string $surname,
    ) {}

    public function id(): Id
    {
        return $this->id;
    }

    public function presentation(): string
    {
        return $this->presentation;
    }

    public function email(): ?string
    {
        return $this->email;
    }

    public function phone(): ?string
    {
        return $this->phone;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function surname(): ?string
    {
        return $this->surname;
    }
}
