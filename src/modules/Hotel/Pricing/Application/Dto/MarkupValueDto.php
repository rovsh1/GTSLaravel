<?php

declare(strict_types=1);

namespace Module\Hotel\Pricing\Application\Dto;

use Module\Shared\Support\Dto\AbstractDomainBasedDto;
use Sdk\Shared\Enum\Pricing\ValueTypeEnum;
use Sdk\Shared\ValueObject\MarkupValue;

class MarkupValueDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $value,
        public readonly ValueTypeEnum $type
    ) {}

    public static function fromDomain(mixed $entity): static
    {
        assert($entity instanceof MarkupValue);

        return new static(
            $entity->value(),
            $entity->type()
        );
    }
}
