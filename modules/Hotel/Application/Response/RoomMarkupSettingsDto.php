<?php

declare(strict_types=1);

namespace Module\Hotel\Application\Response;

use Module\Hotel\Domain\Entity\Room\MarkupSettings;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class RoomMarkupSettingsDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $individual,
        public readonly int $TA,
        public readonly int $OTA,
        public readonly int $TO,
        public readonly int $discount,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|MarkupSettings $entity): static
    {
        return new static(
            $entity->individual()->value(),
            $entity->TA()->value(),
            $entity->OTA()->value(),
            $entity->TO()->value(),
            $entity->discount()->value(),
        );
    }
}
