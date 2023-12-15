<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Domain\Entity;

use Carbon\CarbonPeriodImmutable;
use Module\Client\Moderation\Domain\ValueObject\ContractId;
use Module\Shared\Contracts\Domain\EntityInterface;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;
use Sdk\Shared\Enum\Contract\StatusEnum;

class Contract extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly ContractId $id,
        private readonly string $number,
        private readonly CarbonPeriodImmutable $period,
        private readonly StatusEnum $status,
    ) {}

    public function id(): ContractId
    {
        return $this->id;
    }

    public function number(): string
    {
        return $this->number;
    }

    public function period(): CarbonPeriodImmutable
    {
        return $this->period;
    }

    public function status(): StatusEnum
    {
        return $this->status;
    }
}
