<?php

namespace Module\Services\FileStorage\Application\Command;

use Module\Services\FileStorage\Domain\Entity\File;
use Module\Services\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Services\FileStorage\Domain\Repository\StorageRepositoryInterface;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class CreateFileHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly DatabaseRepositoryInterface $databaseRepository,
        private readonly StorageRepositoryInterface $storageRepository,
    ) {}

    public function handle(CommandInterface|CreateFile $command): File
    {
        $file = $this->databaseRepository->create($command->fileType, $command->entityId, $command->name);

        if (null !== $command->contents) {
            $this->storageRepository->put($file, $command->contents);
        }

        return $file;
    }
}
