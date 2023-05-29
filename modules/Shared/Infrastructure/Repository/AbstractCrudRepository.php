<?php

namespace Module\Shared\Infrastructure\Repository;

use Illuminate\Database\Eloquent\Collection;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

abstract class AbstractCrudRepository
{
    protected string $model;

    public function create(mixed $dto)
    {
        $model = $this->model::create($this->mapDtoToData($dto));

        return $this->createEntityFromModel($model);
    }

    public function find(int $id)
    {
        $model = $this->tryFindModel($id);

        return $this->createEntityFromModel($model);
    }

    public function update(int $id, mixed $dto)
    {
        $model = $this->tryFindModel($id);

        $model->update($this->mapDtoToData($dto));

        return $this->createEntityFromModel($model);
    }

    public function delete(int $id): bool
    {
        $this->tryFindModel($id)->delete();

        return true;
    }

    protected function createEntitiesFromModels(Collection $collection): array
    {
        return $collection->map(fn($model) => $this->createEntityFromModel($model))->all();
    }

    abstract protected function createEntityFromModel($model);

    protected function mapDtoToData(mixed $dto): array
    {
        return (array)$dto;
    }

    protected function findModel(int $id)
    {
        return $this->model::find($id);
    }

    private function tryFindModel(int $id)
    {
        if (!($model = $this->findModel($id)))
            throw new EntityNotFoundException('Country not found');

        return $model;
    }
}
