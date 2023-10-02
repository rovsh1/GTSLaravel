<?php

declare(strict_types=1);

namespace Module\Supplier\Infrastructure\Repository;

use Module\Booking\Airport\Domain\ValueObject\ContractId;
use Module\Shared\Domain\ValueObject\Date;
use Module\Shared\Enum\Supplier\ContractServiceTypeEnum;
use Module\Supplier\Domain\Supplier\Entity\Contract;
use Module\Supplier\Domain\Supplier\Repository\ContractRepositoryInterface;
use Module\Supplier\Domain\Supplier\ValueObject\ServiceId;
use Module\Supplier\Domain\Supplier\ValueObject\SupplierId;
use Module\Supplier\Infrastructure\Models\Contract as Model;

class ContractRepository implements ContractRepositoryInterface
{
    public function find(int $serviceId, ContractServiceTypeEnum $serviceType): ?Contract
    {
        $model = Model::whereServiceId($serviceId)
            ->whereServiceType($serviceType)
            ->whereActive()
            ->first();

        if ($model === null) {
            return null;
        }

        return $this->buildEntityFromModel($model);
    }

    private function buildEntityFromModel(Model $contract): Contract
    {
        return new Contract(
            new ContractId($contract->id),
            new SupplierId($contract->supplier_id),
            new ServiceId($contract->service_id),
            $contract->service_type,
            new Date($contract->date_start),
            new Date($contract->date_end),
        );
    }
}
