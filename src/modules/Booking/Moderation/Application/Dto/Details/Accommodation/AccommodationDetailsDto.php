<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\Details\Accommodation;

use Module\Booking\Moderation\Application\Dto\Details\ConditionDto;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationDetails;

class AccommodationDetailsDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $rateId,
        public readonly bool $isResident,
        public readonly int $discount,
        public readonly ConditionDto|null $earlyCheckIn = null,
        public readonly ConditionDto|null $lateCheckOut = null,
        public readonly string|null $guestNote = null,
    ) {}

    public static function fromDomain(mixed $entity): static
    {
        assert($entity instanceof AccommodationDetails);

        return new static(
            $entity->rateId(),
            $entity->isResident(),
            $entity->discount()->value(),
            $entity->earlyCheckIn() !== null ? ConditionDto::fromDomain($entity->earlyCheckIn()) : null,
            $entity->lateCheckOut() !== null ? ConditionDto::fromDomain($entity->lateCheckOut()) : null,
            $entity->guestNote(),
        );
    }
}
