<?php

declare(strict_types=1);

namespace Module\Client\Shared\Domain\Entity;

use Module\Client\Shared\Domain\ValueObject\ClientId;
use Sdk\Module\Contracts\EntityInterface;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;
use Sdk\Shared\Enum\Client\LanguageEnum;
use Sdk\Shared\Enum\Client\ResidencyEnum;
use Sdk\Shared\Enum\Client\TypeEnum;

class Client extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly ClientId $id,
        private string $name,
        private readonly TypeEnum $type,
        private readonly LanguageEnum $language,
        private ResidencyEnum $residency,
    ) {}

    public function id(): ClientId
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

    public function type(): TypeEnum
    {
        return $this->type;
    }

    public function language(): LanguageEnum
    {
        return $this->language;
    }

    public function residency(): ResidencyEnum
    {
        return $this->residency;
    }

    public function setResidency(ResidencyEnum $residency): void
    {
        $this->residency = $residency;
    }
}