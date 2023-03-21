<?php

namespace Module\Services\FileStorage\Infrastructure\Repository;

use Custom\Framework\Foundation\Exception\EntityNotFoundException;
use Module\Services\FileStorage\Domain\Entity\File;
use Module\Services\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Services\FileStorage\Domain\Repository\StorageRepositoryInterface;
use Module\Services\FileStorage\Infrastructure\Model\File as Model;

class DatabaseRepository implements DatabaseRepositoryInterface
{
    public function find(string $guid): ?File
    {
        $model = Model::findByGuid($guid);

        return $model ? DataMapper::modelToFile($model) : null;
    }

    public function getEntityFile(string $fileType, ?int $entityId): ?File
    {
        $model = Model::whereType($fileType)
            ->whereEntity($entityId)
            ->first();

        return $model ? DataMapper::modelToFile($model) : null;
    }

    public function getEntityFiles(string $fileType, ?int $entityId)
    {
        return Model::whereType($fileType)
            ->whereEntity($entityId)
            ->get()
            ->map(fn($r) => DataMapper::modelToFile($r));
    }

    public function create(string $fileType, ?int $entityId, string $name = null): File
    {
        $model = Model::createFromParent($fileType, $entityId, $name);

        return DataMapper::modelToFile($model);
    }

    public function update(string $guid, array $attributes): bool
    {
        $model = $this->tryFindModel($guid);

        $model->update($attributes);

        return true;
    }

    public function delete(string $guid): bool
    {
        $model = $this->tryFindModel($guid);

        $model->delete();

        return true;
    }

    public function touch(string $guid): void
    {
        $model = $this->tryFindModel($guid);

        $model->touch();
    }

    private function tryFindModel(string $guid): Model
    {
        $model = Model::findByGuid($guid);
        if (!$model) {
            throw new EntityNotFoundException('File [' . $guid . '] not found');
        }

        return $model;
    }
}
