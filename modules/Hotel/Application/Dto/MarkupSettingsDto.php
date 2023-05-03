<?php

declare(strict_types=1);

namespace Module\Hotel\Application\Dto;

use Module\Hotel\Domain\ValueObject\Options\Condition;
use Module\Hotel\Domain\ValueObject\Options\MarkupSettings;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class MarkupSettingsDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $vat,
        public readonly int $touristTax,
        public readonly array $clientMarkups,
        public readonly ?array $earlyCheckIn,
        public readonly ?array $lateCheckOut,
        public readonly array $cancelPeriods,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|MarkupSettings $entity): self
    {
        //@todo конвертация VO в DTO для ClientMarkups, EarlyCheckInCollection, LateCheckOutCollection, CancelPeriodCollection
        return new self(
            $entity->vat()->value(),
            $entity->touristTax()->value(),
            $entity->clientMarkups()->toData(),
            $entity->earlyCheckIn()->toData(),
            $entity->lateCheckOut()->toData(),
            $entity->cancelPeriods()->toData(),
        );
    }
}
