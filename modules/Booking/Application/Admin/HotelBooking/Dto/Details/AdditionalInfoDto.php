<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\Dto\Details;

use Module\Booking\Application\Admin\HotelBooking\Dto\Details\AdditionalInfo\ExternalNumberDto;
use Module\Booking\Domain\Shared\Entity\Details\AdditionalInfoInterface;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

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
