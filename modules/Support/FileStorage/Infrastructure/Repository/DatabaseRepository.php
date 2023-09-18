<?php

namespace Module\Support\FileStorage\Infrastructure\Repository;

use Module\Support\FileStorage\Domain\Entity\File;
use Module\Support\FileStorage\Domain\Exception\FileNotFoundException;
use Module\Support\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Support\FileStorage\Domain\ValueObject\Guid;
use Module\Support\FileStorage\Infrastructure\Model\File as Model;
use Module\Support\FileStorage\Infrastructure\Service\DataMapper;

class DatabaseRepository implements DatabaseRepositoryInterface
{
    public function find(Guid $guid): ?File
    {
        $model = Model::findByGuid($guid->value());

        return $model ? DataMapper::modelToFile($model) : null;
    }

    public function create(string $name = null): File
    {
        $model = Model::create([
            'name' => $name
        ]);

        return DataMapper::modelToFile($model);
    }

    public function store(File $file): bool
    {
        $model = $this->tryFindModel($file->guid()->value());

        $model->update([
            'name' => $file->name()
        ]);

        return true;
    }

    public function delete(File $file): bool
    {
        $model = $this->tryFindModel($file->guid()->value());

        $model->delete();

        return true;
    }

    public function touch(File $file): void
    {
        $model = $this->tryFindModel($file->guid()->value());

        $model->touch();
    }

    private function tryFindModel(string $guid): Model
    {
        $model = Model::findByGuid($guid);
        if (!$model) {
            throw new FileNotFoundException('File [' . $guid . '] not found');
        }

        return $model;
    }

    private static function findExtension(?string $name): ?string
    {
        if (empty($name) || false === ($pos = strrpos($name, '.'))) {
            return null;
        }

        return substr($name, $pos);
    }
}
