<?php

namespace Module\Booking\Airport\Domain\Entity;

use Carbon\CarbonPeriodImmutable;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Id;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Season extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly Id $id,
        private string $name,
        private CarbonPeriodImmutable $period,
    ) {}

    public function id(): Id
    {
        return $this->id;
    }
}
