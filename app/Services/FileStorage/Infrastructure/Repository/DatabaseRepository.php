<?php

namespace GTS\Services\FileStorage\Infrastructure\Repository;

use GTS\Services\FileStorage\Domain\Entity\File;
use GTS\Services\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use GTS\Services\FileStorage\Domain\Repository\StorageRepositoryInterface;
use GTS\Services\FileStorage\Infrastructure\Model\File as Model;
use GTS\Shared\Domain\Exception\EntityNotFoundException;

class DatabaseRepository implements DatabaseRepositoryInterface
{
    public function __construct(private readonly StorageRepositoryInterface $storageRepository) {}

    public function find(string $guid): ?File
    {
        $model = Model::findByGuid($guid);

        return $model ? DataMapper::modelToFile($model) : null;
    }

    public function findEntityImage(string $fileType, ?int $entityId): ?File
    {
        $model = Model::whereType($fileType)
            ->whereParent($entityId)
            ->first();

        return $model ? DataMapper::modelToFile($model) : null;
    }

    public function getEntityImages(string $fileType, ?int $entityId)
    {
        return Model::whereType($fileType)
            ->whereParent($entityId)
            ->get()
            ->map(fn($r) => DataMapper::modelToFile($r));
    }

    public function create(string $fileType, ?int $entityId, ?string $name): File
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

        $this->storageRepository->delete($guid);

        $model->delete();

        return true;
    }

    public function put(string $guid, string $contents): bool
    {
        $model = $this->tryFindModel($guid);

        $this->storageRepository->put($guid, $contents);

        $model->touch();

        return true;
    }

    private function tryFindModel(string $guid): Model
    {
        $model = Model::findByGuid($guid);
        if (!$model)
            throw new EntityNotFoundException('File [' . $guid . '] not found');

        return $model;
    }
}
