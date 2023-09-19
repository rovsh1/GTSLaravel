<?php

namespace Module\Support\FileStorage\Application\Service;

use Module\Support\FileStorage\Domain\Entity\File;
use Module\Support\FileStorage\Domain\Repository\CacheRepositoryInterface;
use Module\Support\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Support\FileStorage\Domain\ValueObject\Guid;

class FileReader
{
    public function __construct(
        public readonly DatabaseRepositoryInterface $databaseRepository,
        private readonly CacheRepositoryInterface $cacheRepository,
    ) {
    }

    public function findByGuid(string $guid): ?File
    {
        $guid = new Guid($guid);
        $file = $this->cacheRepository->get($guid) ?? $this->databaseRepository->find($guid);
        if (null === $file) {
            return null;
        }

        $this->cacheRepository->store($file);

        return $file;
    }
}
