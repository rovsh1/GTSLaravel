<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Admin\ResponseDto\MarkupSettings;

use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\CancelMarkupOption;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class CancelMarkupOptionDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $percent,
        public readonly int $cancelPeriodType
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|CancelMarkupOption $entity): static
    {
        return new static(
            $entity->percent()->value(),
            $entity->cancelPeriodType()->value
        );
    }
}
