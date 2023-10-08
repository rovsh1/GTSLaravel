<?php

declare(strict_types=1);

namespace Module\Catalog\Application\Admin\ResponseDto\MarkupSettings;

use Module\Catalog\Domain\Hotel\ValueObject\MarkupSettings\ClientMarkups;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

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
