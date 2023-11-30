<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\Response;

use Module\Shared\Support\Dto\AbstractDomainBasedDto;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\DailyMarkupOption;

class DailyMarkupDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $percent,
        public readonly int $daysCount
    ) {}

    public static function fromDomain(mixed $entity): static
    {
        assert($entity instanceof DailyMarkupOption);

        return new static(
            $entity->percent()->value(),
            $entity->daysCount()
        );
    }
}
