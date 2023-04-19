<?php

namespace Module\Services\FileStorage\Domain\Service;

use Module\Services\FileStorage\Domain\Entity\File;

interface PathGeneratorInterface
{
    public function relativePath(File $file, int $part = null): string;

    public function path(File $file, int $part = null): string;
}
