<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Infrastructure\Repository;

use Carbon\CarbonPeriodImmutable;
use Module\Client\Moderation\Domain\Entity\Contract;
use Module\Client\Moderation\Domain\Repository\ContractRepositoryInterface;
use Module\Client\Moderation\Domain\ValueObject\ContractId;
use Module\Client\Moderation\Infrastructure\Models\Document;
use Module\Client\Shared\Domain\ValueObject\ClientId;

class ContractRepository implements ContractRepositoryInterface
{
    public function findActive(ClientId $clientId): ?Contract
    {
        $model = Document::onlyContracts()->whereActive()->whereClientId($clientId->value())->first();
        if ($model === null) {
            return null;
        }

        return $this->buildFromModel($model);
    }

    private function buildFromModel(Document $model): Contract
    {
        return new Contract(
            id: new ContractId($model->id),
            number: $model->number,
            period: new CarbonPeriodImmutable($model->date_start, $model->date_end),
            status: $model->status,
        );
    }
}
