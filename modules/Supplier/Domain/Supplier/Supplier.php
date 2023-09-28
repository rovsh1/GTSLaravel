<?php

declare(strict_types=1);

namespace Module\Supplier\Domain\Supplier;

use Module\Shared\Domain\Entity\EntityInterface;
use Module\Supplier\Domain\Supplier\ValueObject\Requisites;
use Module\Supplier\Domain\Supplier\ValueObject\SupplierId;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Supplier extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly SupplierId $id,
        private string $name,
        private ?Requisites $requisites,
    ) {}

    public function id(): SupplierId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function requisites(): ?Requisites
    {
        return $this->requisites;
    }
}
