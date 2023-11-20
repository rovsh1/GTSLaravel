<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\UseCase\CancelConditions;

use Module\Supplier\Moderation\Application\RequestDto\UpdateTransferCancelConditionsRequest;
use Module\Supplier\Moderation\Domain\Supplier\Repository\CancelConditionsRepositoryInterface;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CancelConditions;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateTransferCancelConditions implements UseCaseInterface
{
    public function __construct(
        private readonly CancelConditionsRepositoryInterface $cancelConditionsRepository
    ) {
    }

    public function execute(UpdateTransferCancelConditionsRequest $request): void
    {
        $cancelConditions = CancelConditions::fromData($request->cancelConditions);

        //@todo понять как хранить и обновлять их
        $this->cancelConditionsRepository->store($cancelConditions);
    }
}
