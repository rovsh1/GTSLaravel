<?php

namespace Module\Support\FileStorage\Application\Command;

use Sdk\Module\Contracts\Bus\CommandInterface;

class DeleteFile implements CommandInterface
{
    public function __construct(
        public readonly string $guid,
    ) {}
}
