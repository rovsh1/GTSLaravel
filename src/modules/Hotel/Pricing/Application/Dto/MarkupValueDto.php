<?php

declare(strict_types=1);

namespace Module\Hotel\Pricing\Application\Dto;

use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Enum\Pricing\MarkupValueTypeEnum;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;
use Module\Shared\ValueObject\MarkupValue;

class MarkupValueDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $value,
        public readonly MarkupValueTypeEnum $type
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface $entity): static
    {
        assert($entity instanceof MarkupValue);

        return new static(
            $entity->value()->value(),
            $entity->type()
        );
    }
}
