<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\Details;

use Module\Shared\Support\Dto\AbstractDomainBasedDto;
use Sdk\Booking\ValueObject\HotelBooking\ExternalNumber;

class ExternalNumberDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $type,
        public readonly ?string $number
    ) {}

    public static function fromDomain(mixed $entity): static
    {
        assert($entity instanceof ExternalNumber);

        return new static(
            $entity->type()->value,
            $entity->number()
        );
    }
}
