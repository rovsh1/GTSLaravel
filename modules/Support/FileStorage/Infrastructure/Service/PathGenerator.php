<?php

namespace Module\Support\FileStorage\Infrastructure\Service;

use Module\Support\FileStorage\Domain\Entity\File;
use Module\Support\FileStorage\Domain\Service\PathGeneratorInterface;

class PathGenerator implements PathGeneratorInterface
{
    private readonly string $rootPath;

    public function __construct(
        string $rootPath,
        private readonly int $nestingLevel,
        private readonly int $pathNameLength,
    ) {
        $this->rootPath = rtrim($rootPath, '/');
    }

    public function relativePath(File $file, int $part = null): string
    {
        return implode(DIRECTORY_SEPARATOR, $this->guidPaths($file->guid()->value()))
            . DIRECTORY_SEPARATOR . $file->guid()->value()
            . DIRECTORY_SEPARATOR . $file->name()
//            . ($part ? '_' . $part : '')
//            . $file->extension()
            ;
    }

    public function path(File $file, int $part = null): string
    {
        return $this->rootPath . DIRECTORY_SEPARATOR . $this->relativePath($file, $part);
    }

    private function guidPaths(string $guid): array
    {
        $paths = [];
        for ($i = 0; $i < $this->nestingLevel; $i++) {
            $paths[] = substr($guid, $i * $this->pathNameLength, $this->pathNameLength);
        }

        return $paths;
    }
}
