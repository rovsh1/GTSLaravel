<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\UseCase\CancelConditions;

use Module\Supplier\Moderation\Application\Response\CancelConditionsDto;
use Module\Supplier\Moderation\Domain\Supplier\Repository\TransferCancelConditionsRepositoryInterface;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CarId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\SeasonId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\ServiceId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetTransferCancelConditions implements UseCaseInterface
{
    public function __construct(
        private readonly TransferCancelConditionsRepositoryInterface $cancelConditionsRepository
    ) {
    }

    public function execute(int $seasonId, int $serviceId, int $carId): ?CancelConditionsDto
    {
        $cancelConditions = $this->cancelConditionsRepository->get(
            serviceId: new ServiceId($serviceId),
            carId: new CarId($carId),
            seasonId: new SeasonId($seasonId),
        );
        if ($cancelConditions === null) {
            return null;
        }

        return CancelConditionsDto::fromDomain($cancelConditions);
    }
}
