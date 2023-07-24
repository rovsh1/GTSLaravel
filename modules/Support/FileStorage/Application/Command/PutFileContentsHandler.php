<?php

namespace Module\Support\FileStorage\Application\Command;

use Module\Support\FileStorage\Domain\Repository\DatabaseRepositoryInterface;
use Module\Support\FileStorage\Domain\Repository\StorageRepositoryInterface;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

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
