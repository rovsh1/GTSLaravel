<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\Dto\Details;

use Module\Booking\Common\Domain\Entity\Details\AdditionalInfoInterface;
use Module\Booking\HotelBooking\Application\Dto\Details\AdditionalInfo\ExternalNumberDto;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class AdditionalInfoDto extends AbstractDomainBasedDto
{
    public function __construct(
       public readonly ?ExternalNumberDto $externalNumber
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|AdditionalInfoInterface $entity): static
    {
        return new static(
            ExternalNumberDto::fromDomain($entity->externalNumber())
        );
    }
}
