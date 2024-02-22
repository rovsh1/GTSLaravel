<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\Response;

use Module\Shared\Support\Dto\AbstractDomainBasedDto;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CancelConditions;

class CancelConditionsDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly CancelMarkupOptionDto $noCheckInMarkup,
        /** @var DailyMarkupDto[] $dailyMarkups */
        public readonly array $dailyMarkups
    ) {}

    public static function fromDomain(mixed $entity): static
    {
        assert($entity instanceof CancelConditions);

        return new static(
            CancelMarkupOptionDto::fromDomain($entity->noCheckInMarkup()),
            DailyMarkupDto::collectionFromDomain($entity->dailyMarkups()->all())
        );
    }
}
