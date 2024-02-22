<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Application\Admin\Dto;

use Carbon\CarbonPeriodImmutable;
use Module\Client\Moderation\Domain\Entity\Contract;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class ContractDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly string $number,
        public readonly CarbonPeriodImmutable $period,
    ) {}

    public static function fromDomain(mixed $entity): static
    {
        assert($entity instanceof Contract);

        return new static(
            $entity->number(),
            $entity->period()
        );
    }
}
