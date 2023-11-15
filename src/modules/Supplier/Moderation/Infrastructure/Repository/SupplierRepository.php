<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Infrastructure\Repository;

use Module\Shared\Enum\CurrencyEnum;
use Module\Supplier\Moderation\Domain\Supplier\Repository\SupplierRepositoryInterface;
use Module\Supplier\Moderation\Domain\Supplier\Supplier;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\Requisites;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\SupplierId;
use Module\Supplier\Moderation\Infrastructure\Models\Supplier as Model;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function find(int $id): ?Supplier
    {
        $model = Model::find($id);
        if ($model === null) {
            return null;
        }

        return $this->buildEntityFromModel($model);
    }

    private function buildEntityFromModel(Model $supplier): Supplier
    {
        $requisites = $supplier->requisites;

        return new Supplier(
            id: new SupplierId($supplier->id),
            name: $supplier->name,
            currency: CurrencyEnum::from($supplier->currency),
            requisites: $requisites
                ? new Requisites(
                    inn: $requisites->inn,
                    directorFullName: $requisites->director_full_name
                )
                : null,
        );
    }
}
