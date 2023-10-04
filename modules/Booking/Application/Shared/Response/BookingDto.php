<?php

declare(strict_types=1);

namespace Module\Booking\Application\Shared\Response;

use Carbon\CarbonImmutable;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

abstract class BookingDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly StatusDto $status,
        public readonly int $orderId,
        public readonly CarbonImmutable $createdAt,
        public readonly int $creatorId,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|BookingInterface $entity): static
    {
        return new static(
            $entity->id()->value(),
            $entity->status()->value,
            $entity->orderId()->value(),
            $entity->createdAt(),
            $entity->creatorId()->value(),
        );
    }
}