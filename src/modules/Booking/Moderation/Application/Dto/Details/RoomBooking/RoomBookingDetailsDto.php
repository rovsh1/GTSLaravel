<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\Details\RoomBooking;

use Module\Booking\Moderation\Application\Dto\Details\ConditionDto;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomBookingDetails;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class RoomBookingDetailsDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $rateId,
        public readonly bool $isResident,
        public readonly ConditionDto|null $earlyCheckIn = null,
        public readonly ConditionDto|null $lateCheckOut = null,
        public readonly string|null $guestNote = null,
        public readonly int $discount,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|RoomBookingDetails $entity): static
    {
        return new static(
            $entity->rateId(),
            $entity->isResident(),
            $entity->earlyCheckIn() !== null ? ConditionDto::fromDomain($entity->earlyCheckIn()) : null,
            $entity->lateCheckOut() !== null ? ConditionDto::fromDomain($entity->lateCheckOut()) : null,
            $entity->guestNote(),
            $entity->discount()->value()
        );
    }
}