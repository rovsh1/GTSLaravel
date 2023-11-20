<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\UseCase\CancelConditions;

use Module\Supplier\Moderation\Application\Response\CancelConditionsDto;
use Module\Supplier\Moderation\Domain\Supplier\Repository\AirportCancelConditionsRepositoryInterface;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\AirportId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\SeasonId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\ServiceId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAirportCancelConditions implements UseCaseInterface
{
    public function __construct(
        private readonly AirportCancelConditionsRepositoryInterface $cancelConditionsRepository
    ) {
    }

    public function execute(): ?CancelConditionsDto
    {
        $cancelConditions = $this->cancelConditionsRepository->get(
            serviceId: new ServiceId(0),
            airportId: new AirportId(0),
            seasonId: new SeasonId(0),
        );
        if ($cancelConditions === null) {
            return null;
        }

        return CancelConditionsDto::fromDomain($cancelConditions);
    }
}
