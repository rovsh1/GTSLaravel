<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\Dto\Details;

use Carbon\CarbonInterface;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\CancelConditions\CancelMarkupOptionDto;
use Module\Booking\Application\Admin\HotelBooking\Dto\Details\CancelConditions\DailyMarkupDto;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class CancelConditionsDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly CancelMarkupOptionDto $noCheckInMarkup,
        public readonly array $dailyMarkups,
        public readonly ?CarbonInterface $cancelNoFeeDate
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|CancelConditions $entity): static
    {
        return new static(
            CancelMarkupOptionDto::fromDomain($entity->noCheckInMarkup()),
            DailyMarkupDto::collectionFromDomain($entity->dailyMarkups()->all()),
            $entity->cancelNoFeeDate()
        );
    }
}
