<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\UseCase;

use Module\Supplier\Moderation\Application\Response\ServiceContractDto;
use Module\Supplier\Moderation\Domain\Supplier\Repository\ContractRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class FindAirportServiceContract implements UseCaseInterface
{
    public function __construct(
        private readonly ContractRepositoryInterface $contractRepository,
    ) {}

    public function execute(int $serviceId): ?ServiceContractDto
    {
        $contract = $this->contractRepository->find($serviceId);
        if ($contract === null) {
            return null;
        }

        return ServiceContractDto::fromDomain($contract);
    }
}
