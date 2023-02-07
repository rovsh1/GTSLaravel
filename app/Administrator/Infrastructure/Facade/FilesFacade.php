<?php

namespace GTS\Administrator\Infrastructure\Facade;

use GTS\Administrator\Domain\Adapter\FilesAdapterInterface;

class FilesFacade implements FilesFacadeInterface
{
    public function __construct(
        private readonly FilesAdapterInterface $filesAdapter
    ) {}

    public function create()
    {
        return $this->filesAdapter->create('test', 3);
    }
}
