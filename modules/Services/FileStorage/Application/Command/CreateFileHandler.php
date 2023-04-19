<?php

namespace Module\Services\FileStorage\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Services\FileStorage\Domain\Entity\File;
use Module\Services\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Services\FileStorage\Domain\Repository\StorageRepositoryInterface;

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
