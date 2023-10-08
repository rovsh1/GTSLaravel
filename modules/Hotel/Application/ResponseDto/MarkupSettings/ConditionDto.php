<?php

declare(strict_types=1);

namespace Module\Hotel\Application\ResponseDto\MarkupSettings;

use Module\Hotel\Domain\ValueObject\MarkupSettings\Condition;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

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
