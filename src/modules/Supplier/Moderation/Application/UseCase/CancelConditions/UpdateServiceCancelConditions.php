<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\UseCase\CancelConditions;

use Module\Supplier\Moderation\Application\RequestDto\UpdateServiceCancelConditionsRequest;
use Module\Supplier\Moderation\Domain\Supplier\Repository\ServiceCancelConditionsRepositoryInterface;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CancelConditions;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\SeasonId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\ServiceId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateServiceCancelConditions implements UseCaseInterface
{
    public function __construct(
        private readonly ServiceCancelConditionsRepositoryInterface $cancelConditionsRepository
    ) {}

    public function execute(UpdateServiceCancelConditionsRequest $request): void
    {
        $cancelConditions = CancelConditions::deserialize($request->cancelConditions);

        $this->cancelConditionsRepository->store(
            serviceId: new ServiceId($request->serviceId),
            seasonId: new SeasonId($request->seasonId),
            cancelConditions: $cancelConditions,
        );
    }
}
