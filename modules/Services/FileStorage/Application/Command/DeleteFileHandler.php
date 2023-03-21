<?php

namespace Module\Services\FileStorage\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Services\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Services\FileStorage\Domain\Repository\StorageRepositoryInterface;

class DeleteFileHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly DatabaseRepositoryInterface $databaseRepository,
        private readonly StorageRepositoryInterface $storageRepository,
    ) {}

    public function handle(CommandInterface|DeleteFile $command): bool
    {
        $this->databaseRepository->delete($command->guid);

        $this->storageRepository->delete($command->guid);

        return true;
    }
}
