<?php

namespace Module\Services\FileStorage\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Services\FileStorage\Domain\Repository\StorageRepositoryInterface;

class PutFileContentsHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly StorageRepositoryInterface $storageRepository,
    ) {}

    public function handle(CommandInterface|PutFileContents $command): bool
    {
        $this->storageRepository->put($command->guid, $command->contents);

        return true;
    }
}
