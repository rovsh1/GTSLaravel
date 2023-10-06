<?php

declare(strict_types=1);

namespace Module\Pricing\Application\Dto;

use Module\Pricing\Domain\Markup\ValueObject\MarkupValue;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;
use Module\Shared\Enum\Pricing\MarkupValueTypeEnum;

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
