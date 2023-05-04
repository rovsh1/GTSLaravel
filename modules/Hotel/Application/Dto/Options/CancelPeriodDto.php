<?php

declare(strict_types=1);

namespace Module\Hotel\Application\Dto\Options;

use Module\Hotel\Domain\ValueObject\Options\CancelPeriod;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Application\Dto\PeriodDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class CancelPeriodDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly PeriodDto $period,
        public readonly CancelMarkupOptionDto $noCheckInMarkup,
        public readonly array $dailyMarkups
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|CancelPeriod $entity): static
    {
        return new static(
            PeriodDto::fromCarbonPeriod($entity->period()),
            CancelMarkupOptionDto::fromDomain($entity->noCheckInMarkup()),
            DailyMarkupDto::collectionFromDomain($entity->dailyMarkups()->all())
        );
    }
}
