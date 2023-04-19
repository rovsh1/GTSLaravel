<?php

namespace Module\Services\FileStorage\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Services\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Services\FileStorage\Domain\Repository\StorageRepositoryInterface;

class PutFileContentsHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly DatabaseRepositoryInterface $databaseRepository,
        private readonly StorageRepositoryInterface $storageRepository,
    ) {}

    public function handle(CommandInterface|PutFileContents $command): bool
    {
        $file = $this->databaseRepository->find($command->guid);

        if (!$file) {
            return false;
        }

        $this->databaseRepository->touch($file);

        $this->storageRepository->put($file, $command->contents);

        return true;
    }
}
