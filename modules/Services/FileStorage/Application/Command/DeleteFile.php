<?php

namespace Module\Services\FileStorage\Application\Command;

use Custom\Framework\Contracts\Bus\CommandInterface;

class DeleteFile implements CommandInterface
{
    public function __construct(
        public readonly string $guid,
    ) {}
}
