<?php

declare(strict_types=1);

namespace Module\Hotel\Application\ResponseDto\MarkupSettings;

use Module\Hotel\Domain\ValueObject\MarkupSettings\DailyMarkupOption;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class DailyMarkupDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $percent,
        public readonly int $cancelPeriodType,
        public readonly int $daysCount
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|DailyMarkupOption $entity): static
    {
        return new static(
            $entity->percent()->value(),
            $entity->cancelPeriodType()->value,
            $entity->daysCount()
        );
    }
}
