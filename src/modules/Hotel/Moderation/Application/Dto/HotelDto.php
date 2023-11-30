<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Dto;

use Module\Hotel\Moderation\Domain\Hotel\Hotel;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class HotelDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $currency,
        public readonly TimeSettingsDto $timeSettings,
        public readonly string $countryName,
        public readonly string $cityName,
        public readonly string $address,
        /** @var array<int, ContactDto> $contacts */
        public readonly array $contacts,
    ) {}

    public static function fromDomain(mixed $entity): static
    {
        assert($entity instanceof Hotel);

        return new static(
            $entity->id()->value(),
            $entity->name(),
            $entity->currency()->name,
            TimeSettingsDto::fromDomain($entity->timeSettings()),
            $entity->address()->country(),
            $entity->address()->city(),
            $entity->address()->address(),
            ContactDto::collectionFromDomain($entity->contacts()->all())
        );
    }
}
