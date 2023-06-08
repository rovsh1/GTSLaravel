<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Dto;

use Carbon\CarbonInterface;
use Module\Booking\Common\Domain\ValueObject\StatusChangeEvent;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class StatusEventDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly string $event,
        public readonly array $payload,
        public readonly int $source,
        public readonly int $userId,
        public readonly CarbonInterface $dateCreate
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|StatusChangeEvent $entity): static
    {
        return new static(
            $entity->event(),
            [],
            $entity->source(),
            $entity->userId(),
            $entity->dateCreate(),
        );
    }
}
