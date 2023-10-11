<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Shared\Response;

use Carbon\CarbonImmutable;
use Module\Booking\Domain\BookingRequest\BookingRequest;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class RequestDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $type,
        public readonly CarbonImmutable $dateCreate
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|BookingRequest $entity): static
    {
        return new static(
            $entity->id()->value(),
            $entity->type()->value,
            $entity->dateCreate()
        );
    }
}
