<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Domain\Entity;

use Module\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Module\Booking\Requesting\Domain\ValueObject\ChangeStatusEnum;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

final class Changes extends AbstractAggregateRoot
{
    public function __construct(
        private readonly ChangesIdentifier $identifier,
        private ChangeStatusEnum $status,
        private string $description,
        private ?array $payload = null,
    ) {}

    public static function makeCreated(
        ChangesIdentifier $identifier,
        string $description,
        ?array $payload = null
    ): Changes {
        return new Changes($identifier, ChangeStatusEnum::CREATED, $description, $payload);
    }

    public static function makeUpdated(
        ChangesIdentifier $identifier,
        string $description,
        ?array $payload = null
    ): Changes {
        return new Changes($identifier, ChangeStatusEnum::UPDATED, $description, $payload);
    }

    public static function makeDeleted(
        ChangesIdentifier $identifier,
        string $description,
        ?array $payload = null
    ): Changes {
        return new Changes($identifier, ChangeStatusEnum::DELETED, $description, $payload);
    }

    public function identifier(): ChangesIdentifier
    {
        return $this->identifier;
    }

    public function bookingId(): int
    {
        return $this->identifier->bookingId();
    }

    public function field(): string
    {
        return $this->identifier->field();
    }

    public function isCreated(): bool
    {
        return $this->status === ChangeStatusEnum::CREATED;
    }

    public function isUpdated(): bool
    {
        return $this->status === ChangeStatusEnum::UPDATED;
    }

    public function isDeleted(): bool
    {
        return $this->status === ChangeStatusEnum::DELETED;
    }

    public function status(): ChangeStatusEnum
    {
        return $this->status;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function payload(): ?array
    {
        return $this->payload;
    }

    public function setUpdated(): void
    {
        $this->status = ChangeStatusEnum::UPDATED;
    }

    public function setDeleted(): void
    {
        $this->status = ChangeStatusEnum::DELETED;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
