<?php

namespace Module\Support\FileStorage\Domain\Service;

use Module\Support\FileStorage\Domain\Entity\File;

interface PathGeneratorInterface
{
    public function relativePath(File $file): string;

    public function path(File $file, int $part = null): string;
}
