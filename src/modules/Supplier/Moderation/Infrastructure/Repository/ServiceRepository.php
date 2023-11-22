<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Infrastructure\Repository;

use Module\Supplier\Moderation\Domain\Supplier\Entity\Service;
use Module\Supplier\Moderation\Domain\Supplier\Repository\ServiceRepositoryInterface;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\ServiceId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\SupplierId;
use Module\Supplier\Moderation\Infrastructure\Models\Service as Model;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function find(ServiceId $id): ?Service
    {
        $model = Model::find($id->value());
        if ($model === null) {
            return null;
        }

        return $this->buildFromModel($model);
    }

    private function buildFromModel(Model $model): Service
    {
        return new Service(
            new ServiceId($model->id),
            new SupplierId($model->supplier_id),
            $model->title,
            $model->type
        );
    }
}
