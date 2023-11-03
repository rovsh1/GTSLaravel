<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\ValueObject;

class ServiceInfo
{
    public function __construct(
        private readonly int $id,
        private readonly string $title,
        private readonly int $supplierId,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function supplierId(): int
    {
        return $this->supplierId;
    }
}