<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\ValueObject\RequestTypeEnum;
use Module\Shared\Domain\Entity\EntityInterface;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Request extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly int $id,
        private readonly int $bookingId,
        private readonly RequestTypeEnum $type,
        private readonly CarbonImmutable $dateCreate,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function bookingId(): int
    {
        return $this->bookingId;
    }

    public function type(): RequestTypeEnum
    {
        return $this->type;
    }

    public function dateCreate(): CarbonImmutable
    {
        return $this->dateCreate;
    }
}
