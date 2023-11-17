<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\UseCase\CancelConditions;

use Module\Supplier\Moderation\Application\Response\CancelConditionsDto;
use Module\Supplier\Moderation\Domain\Supplier\Repository\CancelConditionsRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetTransferCancelConditions implements UseCaseInterface
{
    public function __construct(
        private readonly CancelConditionsRepositoryInterface $cancelConditionsRepository
    ) {
    }

    public function execute(int $supplierId, int $seasonId, int $serviceId, int $carId): CancelConditionsDto
    {
        $cancelConditions = $this->cancelConditionsRepository->get();

        return CancelConditionsDto::fromDomain($cancelConditions);
    }
}
