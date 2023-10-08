<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\Dto\Details\AdditionalInfo;

use Module\Booking\Domain\HotelBooking\ValueObject\Details\AdditionalInfo\ExternalNumber;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class ExternalNumberDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $type,
        public readonly ?string $number
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|ExternalNumber $entity): static
    {
        return new static(
            $entity->type()->value,
            $entity->number()
        );
    }
}
