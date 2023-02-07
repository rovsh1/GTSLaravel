<?php

namespace GTS\Services\FileStorage\Infrastructure\Facade;

use Custom\Framework\Exception\EntityNotFoundException;
use GTS\Services\FileStorage\Application\Dto\FileDto;
use GTS\Services\FileStorage\Domain\Entity\File;
use GTS\Services\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use GTS\Services\FileStorage\Domain\Repository\StorageRepositoryInterface;
use GTS\Services\FileStorage\Domain\Service\UrlGeneratorInterface;

class WriterFacade implements WriterFacadeInterface
{
    public function __construct(
        private readonly DatabaseRepositoryInterface $databaseRepository,
        private readonly StorageRepositoryInterface $storageRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    public function create(string $fileType, ?int $entityId, string $name = null, string $contents = null): FileDto
    {
        $file = $this->databaseRepository->create($fileType, $entityId, $name);

        if (null !== $contents)
            $this->storageRepository->put($file->guid(), $contents);

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
