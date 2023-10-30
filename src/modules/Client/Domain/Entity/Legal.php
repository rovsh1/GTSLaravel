<?php

declare(strict_types=1);

namespace Module\Client\Domain\Entity;

use Module\Client\Domain\ValueObject\BankRequisites;
use Module\Client\Domain\ValueObject\IndustryId;
use Module\Client\Domain\ValueObject\LegalId;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Enum\Client\LegalTypeEnum;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Legal extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly LegalId $id,
        private string $name,
        private ?IndustryId $industryId,
        private ?string $address,
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

    public function industryId(): ?IndustryId
    {
        return $this->industryId;
    }

    public function setIndustryId(?IndustryId $industryId): void
    {
        $this->industryId = $industryId;
    }

    public function address(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
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
