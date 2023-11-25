<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Infrastructure\Repository;

use Module\Booking\Shared\Domain\Shared\ValueObject\ContractId;
use Module\Supplier\Moderation\Domain\Supplier\Entity\Contract;
use Module\Supplier\Moderation\Domain\Supplier\Repository\ContractRepositoryInterface;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\ServiceIdCollection;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\SupplierId;
use Module\Supplier\Moderation\Infrastructure\Models\Contract as Model;
use Sdk\Shared\ValueObject\Date;

class ContractRepository implements ContractRepositoryInterface
{
    public function find(int $serviceId): ?Contract
    {
        $model = Model::whereServiceId($serviceId)
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
            ServiceIdCollection::fromData($contract->service_ids),
            new Date($contract->date_start),
            new Date($contract->date_end),
        );
    }
}
