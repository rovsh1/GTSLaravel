<?php

declare(strict_types=1);

namespace Module\Supplier\Application\UseCase;

use Module\Shared\Enum\Supplier\ContractServiceTypeEnum;
use Module\Supplier\Application\Response\ServiceContractDto;
use Module\Supplier\Domain\Supplier\Repository\ContractRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class FindTransferServiceContract implements UseCaseInterface
{
    public function __construct(
        private readonly ContractRepositoryInterface $contractRepository,
    ) {}

    public function execute(int $serviceId): ?ServiceContractDto
    {
        $contract = $this->contractRepository->find($serviceId, ContractServiceTypeEnum::TRANSFER);
        if ($contract === null) {
            return null;
        }

        return ServiceContractDto::fromDomain($contract);
    }
}
