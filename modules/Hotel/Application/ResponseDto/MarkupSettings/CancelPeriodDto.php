<?php

declare(strict_types=1);

namespace Module\Hotel\Application\ResponseDto\MarkupSettings;

use Carbon\CarbonInterface;
use Module\Hotel\Domain\ValueObject\MarkupSettings\CancelPeriod;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class CancelPeriodDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly CarbonInterface $from,
        public readonly CarbonInterface $to,
        public readonly CancelMarkupOptionDto $noCheckInMarkup,
        public readonly array $dailyMarkups
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|CancelPeriod $entity): static
    {
        return new static(
            $entity->period()->getStartDate(),
            $entity->period()->getEndDate(),
            CancelMarkupOptionDto::fromDomain($entity->noCheckInMarkup()),
            DailyMarkupDto::collectionFromDomain($entity->dailyMarkups()->all())
        );
    }
}
