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
        $this->databaseRepository->touch($command->guid);

        $this->storageRepository->put($command->guid, $command->contents);

        return true;
    }
}
