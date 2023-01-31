<?php

namespace GTS\Services\FileStorage\Infrastructure\Facade;

use GTS\Services\FileStorage\Application\Dto\FileDto;
use GTS\Services\FileStorage\Domain\Entity\File;
use GTS\Services\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use GTS\Services\FileStorage\Domain\Repository\StorageRepositoryInterface;
use GTS\Services\FileStorage\Domain\Service\UrlGeneratorInterface;
use GTS\Shared\Domain\Exception\EntityNotFoundException;

class WriterFacade implements WriterFacadeInterface
{
    public function __construct(
        private readonly DatabaseRepositoryInterface $databaseRepository,
        private readonly StorageRepositoryInterface $storageRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    public function create(string $fileType, ?int $entityId, string $name = null): FileDto
    {
        $file = $this->databaseRepository->create($fileType, $entityId, $name);

        return (new DataMapper($this->urlGenerator))->fileToDto($file);
    }

    public function put(string $guid, string $contents): bool
    {
        $this->tryFindFile($guid);

        return $this->storageRepository->put($guid, $contents);
    }

    public function delete(string $guid): bool
    {
        $this->tryFindFile($guid);

        return $this->storageRepository->delete($guid);
    }

    private function tryFindFile(string $guid): File
    {
        $file = $this->databaseRepository->find($guid);
        if (!$file)
            throw new EntityNotFoundException('File [' . $guid . '] not found');

        return $file;
    }
}
