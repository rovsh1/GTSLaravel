<?php

declare(strict_types=1);

namespace Module\Administrator\Domain\Entity;

use Module\Administrator\Domain\ValueObject\AdministratorId;
use Sdk\Module\Contracts\EntityInterface;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Administrator extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly AdministratorId $id,
        private string $presentation,
        private ?string $email,
        private ?string $phone,
        private ?string $name,
        private ?string $surname,
        private ?string $post,
    ) {}

    public function id(): AdministratorId
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

    public function post(): ?string
    {
        return $this->post;
    }
}
