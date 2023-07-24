<?php

namespace Module\Support\FileStorage\Application\Command;

use Module\Support\FileStorage\Domain\Entity\File;
use Module\Support\FileStorage\Domain\Repository\CacheRepositoryInterface;
use Module\Support\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Support\FileStorage\Domain\Repository\StorageRepositoryInterface;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class CreateFileHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly DatabaseRepositoryInterface $databaseRepository,
        private readonly StorageRepositoryInterface $storageRepository,
        private readonly CacheRepositoryInterface $cacheRepository,
    ) {}

    public function handle(CommandInterface|CreateFile $command): File
    {
        $file = $this->databaseRepository->create($command->fileType, $command->entityId, $command->name);

        if (null !== $command->contents) {
            $this->storageRepository->put($file, $command->contents);
        }

        $this->cacheRepository->store($file);

        return $file;
    }
}
