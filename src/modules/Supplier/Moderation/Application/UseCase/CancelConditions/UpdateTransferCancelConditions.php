<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\UseCase\CancelConditions;

use Module\Supplier\Moderation\Application\RequestDto\UpdateTransferCancelConditionsRequest;
use Module\Supplier\Moderation\Domain\Supplier\Repository\TransferCancelConditionsRepositoryInterface;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CancelConditions;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CarId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\SeasonId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\ServiceId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateTransferCancelConditions implements UseCaseInterface
{
    public function __construct(
        private readonly TransferCancelConditionsRepositoryInterface $cancelConditionsRepository
    ) {
    }

    public function execute(UpdateTransferCancelConditionsRequest $request): void
    {
        $cancelConditions = CancelConditions::fromData($request->cancelConditions);

        $this->cancelConditionsRepository->store(
            serviceId: new ServiceId($request->serviceId),
            carId: new CarId($request->carId),
            seasonId: new SeasonId($request->seasonId),
            cancelConditions: $cancelConditions,
        );
    }
}
