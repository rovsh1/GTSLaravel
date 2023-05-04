<?php

declare(strict_types=1);

namespace Module\Hotel\Application\Dto\MarkupSettings;

use Module\Hotel\Domain\ValueObject\MarkupSettings\Condition;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class ConditionDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly string $from,
        public readonly string $to,
        public readonly int $percent,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Condition $entity): static
    {
        return new static(
            $entity->timePeriod()->from(),
            $entity->timePeriod()->to(),
            $entity->priceMarkup()->value(),
        );
    }
}
