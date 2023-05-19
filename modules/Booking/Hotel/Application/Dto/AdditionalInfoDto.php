<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Dto;

use Module\Booking\Common\Domain\Entity\Details\AdditionalInfoInterface;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class AdditionalInfoDto extends AbstractDomainBasedDto
{
    public function __construct() {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|AdditionalInfoInterface $entity): static
    {
        return new static();
    }
}
