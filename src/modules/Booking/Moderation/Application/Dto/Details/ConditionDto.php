<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Dto\Details;

use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;
use Sdk\Booking\ValueObject\HotelBooking\Condition;

class ConditionDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly string $from,
        public readonly string $to,
        public readonly int $percent,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Condition $entity): static
    {
        return new static(
            $entity->timePeriod()->from(),
            $entity->timePeriod()->to(),
            $entity->priceMarkup()->value(),
        );
    }
}
