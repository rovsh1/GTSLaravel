<?php

declare(strict_types=1);

namespace Module\Supplier\Infrastructure\Supplier\Repository;

use Module\Supplier\Domain\Supplier\Repository\SupplierRepositoryInterface;
use Module\Supplier\Domain\Supplier\Supplier;
use Module\Supplier\Domain\Supplier\ValueObject\Requisites;
use Module\Supplier\Domain\Supplier\ValueObject\SupplierId;
use Module\Supplier\Infrastructure\Supplier\Models\Supplier as Model;

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
            requisites: $requisites
                ? new Requisites(
                    inn: $requisites->inn,
                    directorFullName: $requisites->director_full_name
                )
                : null,
        );
    }
}
