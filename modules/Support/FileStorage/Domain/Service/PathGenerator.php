<?php

namespace Module\Support\FileStorage\Domain\Service;

use Module\Support\FileStorage\Domain\Entity\File;

class PathGenerator implements PathGeneratorInterface
{
    private readonly string $rootPath;

    public function __construct(
        string $rootPath,
        private readonly string $pathSeparator,
        private readonly int $nestingLevel,
        private readonly int $pathNameLength,
    ) {
        $this->rootPath = rtrim($rootPath, '/');
    }

    public function relativePath(File $file, int $part = null): string
    {
        return implode($this->pathSeparator, $this->guidPaths($file->guid()))
            . $this->pathSeparator . $file->guid()
            . $this->pathSeparator . $file->name()
//            . ($part ? '_' . $part : '')
//            . $file->extension()
            ;
    }

    public function path(File $file, int $part = null): string
    {
        return $this->rootPath . $this->pathSeparator
            . $this->relativePath($file, $part);
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
