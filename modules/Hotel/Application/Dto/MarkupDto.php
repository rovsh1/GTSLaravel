<?php

declare(strict_types=1);

namespace Module\Hotel\Application\Dto;

use Module\Hotel\Domain\ValueObject\Options\Condition;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class MarkupDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly string $startTime,
        public readonly string $endTime,
        public readonly int $type,
        public readonly int $priceMarkup
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Condition $entity): self
    {
        return new self(
            $entity->timePeriod()->from(),
            $entity->timePeriod()->to(),
            $entity->type()->value,
            $entity->priceMarkup()->value()
        );
    }
}
