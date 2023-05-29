<?php

namespace Module\Services\FileStorage\Application\Command;

use Module\Services\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Services\FileStorage\Domain\Repository\StorageRepositoryInterface;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class DeleteFileHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly DatabaseRepositoryInterface $databaseRepository,
        private readonly StorageRepositoryInterface $storageRepository,
    ) {
    }

    public function handle(CommandInterface|DeleteFile $command): bool
    {
        $file = $this->databaseRepository->find($command->guid);

        if (!$file) {
            return false;
        }

        $this->storageRepository->delete($file);

        $this->databaseRepository->delete($file);

        return true;
    }
}
