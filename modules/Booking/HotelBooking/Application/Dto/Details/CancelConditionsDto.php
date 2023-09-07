<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\Dto\Details;

use Carbon\CarbonInterface;
use Module\Booking\Common\Domain\ValueObject\CancelConditions;
use Module\Booking\HotelBooking\Application\Dto\Details\CancelConditions\CancelMarkupOptionDto;
use Module\Booking\HotelBooking\Application\Dto\Details\CancelConditions\DailyMarkupDto;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

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
