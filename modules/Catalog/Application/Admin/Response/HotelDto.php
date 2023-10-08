<?php

declare(strict_types=1);

namespace Module\Catalog\Application\Admin\Response;

use Module\Catalog\Application\Admin\ResponseDto\ContactDto;
use Module\Catalog\Application\Admin\ResponseDto\TimeSettingsDto;
use Module\Catalog\Domain\Hotel\Entity\Hotel;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
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

    public static function fromDomain(EntityInterface|ValueObjectInterface|Hotel $entity): static
    {
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
