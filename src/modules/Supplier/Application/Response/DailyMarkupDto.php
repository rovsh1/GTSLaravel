<?php

declare(strict_types=1);

namespace Module\Supplier\Application\Response;

use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;
use Module\Supplier\Domain\Supplier\ValueObject\DailyMarkupOption;

class DailyMarkupDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $percent,
        public readonly int $daysCount
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface $entity): static
    {
        assert($entity instanceof DailyMarkupOption);

        return new static(
            $entity->percent()->value(),
            $entity->daysCount()
        );
    }
}
