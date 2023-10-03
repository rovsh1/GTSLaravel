<?php

declare(strict_types=1);

namespace Module\Supplier\Application\UseCase;

use Module\Supplier\Application\Response\CancelConditionsDto;
use Module\Supplier\Domain\Supplier\Repository\CancelConditionsRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetTransferCancelConditions implements UseCaseInterface
{
    public function __construct(
        private readonly CancelConditionsRepositoryInterface $cancelConditionsRepository
    ) {}

    public function execute(): CancelConditionsDto
    {
        $cancelConditions = $this->cancelConditionsRepository->get();

        return CancelConditionsDto::fromDomain($cancelConditions);
    }
}
