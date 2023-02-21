<?php

namespace Module\Services\FileStorage\Application\Command;

use Custom\Framework\Contracts\Bus\CommandInterface;

class CreateFile implements CommandInterface
{
    public function __construct(
        public readonly string $fileType,
        public readonly ?int $entityId,
        public readonly ?string $name,
        public readonly ?string $contents
    ) {}
}
