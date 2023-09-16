<?php

namespace Module\Support\FileStorage\Application\Service;

use Module\Shared\Dto\FileDto;
use Module\Support\FileStorage\Application\Mapper\DataMapper;
use Module\Support\FileStorage\Domain\Entity\File;
use Module\Support\FileStorage\Domain\Exception\FileNotFoundException;
use Module\Support\FileStorage\Domain\Repository\CacheRepositoryInterface;
use Module\Support\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Support\FileStorage\Domain\Repository\StorageRepositoryInterface;
use Module\Support\FileStorage\Domain\ValueObject\Guid;

class FileUpdater
{
    public function __construct(
        private readonly DatabaseRepositoryInterface $databaseRepository,
        private readonly StorageRepositoryInterface $storageRepository,
        private readonly CacheRepositoryInterface $cacheRepository,
        private readonly DataMapper $dataMapper,
    ) {
    }

    public function create(string $name, string $contents): FileDto
    {
        $file = $this->databaseRepository->create($name);

        return $this->store($file, $contents);
    }

    public function update(string $guid, string $name, string $contents): ?FileDto
    {
        $file = $this->databaseRepository->find(new Guid($guid));
        if (!$file) {
            throw new FileNotFoundException();
        }

        $this->storageRepository->delete($file);

        $file->setName($name);
        $this->databaseRepository->store($file);

        return $this->store($file, $contents);
    }

    public function remove(string $guid): void
    {
        $file = $this->databaseRepository->find(new Guid($guid));
        if (null === $file) {
            throw new \Exception('');
        }

        $this->cacheRepository->forget($file);
        $this->storageRepository->delete($file);
        $this->databaseRepository->delete($file);
    }

    private function store(File $file, string $contents): FileDto
    {
        $this->storageRepository->put($file, $contents);

        $this->cacheRepository->store($file);

        return $this->dataMapper->fileToDto($file);
    }
}