<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\UseCase\CancelConditions;

use Module\Supplier\Moderation\Application\RequestDto\UpdateCarCancelConditionsRequest;
use Module\Supplier\Moderation\Domain\Supplier\Repository\CarCancelConditionsRepositoryInterface;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CancelConditions;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CarId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\SeasonId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\ServiceId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateCarCancelConditions implements UseCaseInterface
{
    public function __construct(
        private readonly CarCancelConditionsRepositoryInterface $cancelConditionsRepository
    ) {
    }

    public function execute(UpdateCarCancelConditionsRequest $request): void
    {
        $cancelConditions = CancelConditions::deserialize($request->cancelConditions);

        $this->cancelConditionsRepository->store(
            serviceId: new ServiceId($request->serviceId),
            carId: new CarId($request->carId),
            seasonId: new SeasonId($request->seasonId),
            cancelConditions: $cancelConditions,
        );
    }
}
