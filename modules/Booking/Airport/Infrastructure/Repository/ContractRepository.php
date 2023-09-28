<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Infrastructure\Repository;

use Module\Booking\Airport\Domain\Entity\Contract;
use Module\Booking\Airport\Domain\Repository\ContractRepositoryInterface;
use Module\Booking\Airport\Domain\ValueObject\ContractId;
use Module\Booking\Airport\Infrastructure\Models\Contract as Model;
use Module\Shared\Domain\ValueObject\Date;

class ContractRepository implements ContractRepositoryInterface
{
    public function find(int $serviceId): ?Contract
    {
        $model = Model::whereServiceId($serviceId)->whereActive()->first();

        if ($model === null) {
            return null;
        }

        return $this->buildEntityFromModel($model);
    }

    private function buildEntityFromModel(Model $contract): Contract
    {
        return new Contract(
            new ContractId($contract->id),
            new Date($contract->date_start),
            new Date($contract->date_end),
        );
    }
}
