<?php

namespace Module\Services\FileStorage\Domain\Service;

interface PathGeneratorInterface
{
    public function relativePath(string $guid, int $part = null): string;

    public function path(string $guid, int $part = null): string;
}
