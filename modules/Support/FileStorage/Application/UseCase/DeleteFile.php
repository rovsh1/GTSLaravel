<?php

namespace Module\Support\FileStorage\Application\UseCase;

use Module\Support\FileStorage\Application\Service\FileUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class DeleteFile implements UseCaseInterface
{
    public function __construct(
        private readonly FileUpdater $fileUpdater
    ) {
    }

    public function execute(string $guid): void
    {
        $this->fileUpdater->remove($guid);
    }
}
