<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\UseCase\CancelConditions;

use Module\Supplier\Moderation\Application\Response\CancelConditionsDto;
use Module\Supplier\Moderation\Domain\Supplier\Repository\ServiceCancelConditionsRepositoryInterface;
use Module\Supplier\Moderation\Domain\Supplier\Repository\SeasonRepositoryInterface;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\ServiceId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetServiceCancelConditions implements UseCaseInterface
{
    public function __construct(
        private readonly ServiceCancelConditionsRepositoryInterface $cancelConditionsRepository,
        private readonly SeasonRepositoryInterface $seasonRepository,
    ) {}

    public function execute(int $serviceId, \DateTimeInterface $date): ?CancelConditionsDto
    {
        $serviceId = new ServiceId($serviceId);
        $seasonId = $this->seasonRepository->findActiveSeasonId($serviceId, $date);
        if ($seasonId === null) {
            throw new \RuntimeException('Not found supplier seasons for service');
        }
        $cancelConditions = $this->cancelConditionsRepository->get(
            serviceId: $serviceId,
            seasonId: $seasonId,
        );
        if ($cancelConditions === null) {
            return null;
        }

        return CancelConditionsDto::fromDomain($cancelConditions);
    }
}
