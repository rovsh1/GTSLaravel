<?php

namespace GTS\Services\FileStorage\Domain\Service;

class PathGenerator implements PathGeneratorInterface
{
    public function __construct(
        private readonly string $rootPath,
        private readonly string $pathSeparator,
        private readonly int $nestingLevel,
        private readonly int $pathNameLength,
    ) {}

    public function relativePath(string $guid, int $part = null): string
    {
        return implode($this->pathSeparator, $this->guidPaths($guid))
            . $this->pathSeparator . $guid
            . ($part ? '_' . $part : '');
    }

    public function path(string $guid, int $part = null): string
    {
        return $this->rootPath . $this->pathSeparator
            . $this->relativePath($guid, $part);
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
