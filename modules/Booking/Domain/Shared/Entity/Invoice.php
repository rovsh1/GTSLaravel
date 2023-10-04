<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Entity;

use Carbon\CarbonImmutable;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Id;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Invoice extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly Id $id,
        private readonly Id $bookingId,
        private readonly CarbonImmutable $dateCreate,
    ) {}

    public function id(): Id
    {
        return $this->id;
    }

    public function bookingId(): Id
    {
        return $this->bookingId;
    }

    public function dateCreate(): CarbonImmutable
    {
        return $this->dateCreate;
    }
}