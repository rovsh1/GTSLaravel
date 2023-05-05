<?php

declare(strict_types=1);

namespace Module\Hotel\Application\Dto\MarkupSettings;

use Module\Hotel\Domain\Entity\MarkupSettings;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class MarkupSettingsDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $vat,
        public readonly int $touristTax,
        public readonly ClientMarkupsDto $clientMarkups,
        public readonly array $earlyCheckIn,
        public readonly array $lateCheckOut,
        public readonly array $cancelPeriods,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|MarkupSettings $entity): static
    {
        return new static(
            $entity->vat()->value(),
            $entity->touristTax()->value(),
            ClientMarkupsDto::fromDomain($entity->clientMarkups()),
            ConditionDto::collectionFromDomain($entity->earlyCheckIn()->all()),
            ConditionDto::collectionFromDomain($entity->lateCheckOut()->all()),
            CancelPeriodDto::collectionFromDomain($entity->cancelPeriods()->all()),
        );
    }
}
