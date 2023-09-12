<?php

declare(strict_types=1);

namespace Module\Client\Domain\Entity;

use Module\Client\Domain\ValueObject\BankRequisites;
use Module\Client\Domain\ValueObject\LegalId;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Enum\Client\LegalTypeEnum;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Legal extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly LegalId $id,
        private string $name,
        private readonly LegalTypeEnum $type,
        private ?BankRequisites $requisites,
    ) {}

    public function id(): LegalId
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

    public function type(): LegalTypeEnum
    {
        return $this->type;
    }

    public function requisites(): ?BankRequisites
    {
        return $this->requisites;
    }

    public function setRequisites(?BankRequisites $requisites): void
    {
        $this->requisites = $requisites;
    }
}
