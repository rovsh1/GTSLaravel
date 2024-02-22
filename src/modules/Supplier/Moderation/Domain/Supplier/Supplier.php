<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Domain\Supplier;

use Module\Supplier\Moderation\Domain\Supplier\ValueObject\Requisites;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\SupplierId;
use Sdk\Module\Contracts\EntityInterface;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;
use Sdk\Shared\Enum\CurrencyEnum;

class Supplier extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly SupplierId $id,
        private string $name,
        private ?Requisites $requisites,
        private CurrencyEnum $currency,
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

    public function currency(): CurrencyEnum
    {
        return $this->currency;
    }
}
