<?php

declare(strict_types=1);

namespace Module\Hotel\Application\Dto\MarkupSettings;

use Module\Hotel\Domain\ValueObject\MarkupSettings\ClientMarkups;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class ClientMarkupsDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $individual,
        public readonly int $TA,
        public readonly int $OTA,
        public readonly int $TO,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|ClientMarkups $entity): static
    {
        return new static(
            $entity->individual()->value(),
            $entity->TA()->value(),
            $entity->OTA()->value(),
            $entity->TO()->value(),
        );
    }
}
