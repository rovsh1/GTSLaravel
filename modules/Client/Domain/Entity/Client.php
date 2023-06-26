<?php

declare(strict_types=1);

namespace Module\Client\Domain\Entity;

use Module\Client\Domain\ValueObject\BankRequisites;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Id;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Client extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly Id $id,
        private string $name,
        private BankRequisites $requisites,
    ) {}

    public function id(): Id
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function requisites(): BankRequisites
    {
        return $this->requisites;
    }

    public function setRequisites(BankRequisites $requisites): void
    {
        $this->requisites = $requisites;
    }
}
