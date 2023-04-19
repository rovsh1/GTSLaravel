<?php

namespace Module\Services\FileStorage\Domain\Service;

use Module\Services\FileStorage\Domain\Entity\File;

class UrlGenerator implements UrlGeneratorInterface
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly PathGeneratorInterface $pathGenerator
    ) {}

    public function url(File $file, int $part = null): ?string
    {
        return $this->baseUrl . '/file/' . $this->pathGenerator->relativePath($file, $part);
    }
}
