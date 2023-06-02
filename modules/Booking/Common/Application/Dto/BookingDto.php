<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Dto;

use Carbon\Carbon;
use Module\Booking\Common\Domain\Entity\Booking;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class BookingDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $status,
        public readonly int $orderId,
        public readonly Carbon $dateCreate,
        public readonly int $creatorId,
        public readonly ?string $note,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Booking $entity): static
    {
        return new static(
            $entity->id(),
            $entity->status()->value,
            $entity->orderId(),
            $entity->dateCreate(),
            $entity->creatorId(),
            $entity->note(),
        );
    }
}