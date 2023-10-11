<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Shared\Response;

use Carbon\CarbonImmutable;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\CancelConditionsDto;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

abstract class BookingDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly StatusDto $status,
        public readonly int $orderId,
        public readonly CarbonImmutable $createdAt,
        public readonly int $creatorId,
        public readonly BookingPriceDto $prices,
        public readonly CancelConditionsDto $cancelConditions,
        public readonly ?string $note,
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
