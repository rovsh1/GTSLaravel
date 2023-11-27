<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\Details;

use Carbon\CarbonInterface;
use Module\Booking\Moderation\Application\Dto\Details\CancelConditions\CancelFeeValueDto;
use Module\Booking\Moderation\Application\Dto\Details\CancelConditions\DailyCancelFeeValueDto;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;
use Sdk\Booking\ValueObject\CancelConditions;

class CancelConditionsDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly CancelFeeValueDto $noCheckInMarkup,
        public readonly array $dailyMarkups,
        public readonly ?CarbonInterface $cancelNoFeeDate
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|CancelConditions $entity): static
    {
        return new static(
            CancelFeeValueDto::fromDomain($entity->noCheckInMarkup()),
            DailyCancelFeeValueDto::collectionFromDomain($entity->dailyMarkups()->all()),
            $entity->cancelNoFeeDate()
        );
    }
}
