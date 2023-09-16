<?php

namespace Module\Support\FileStorage\Infrastructure\Service;

use Module\Support\FileStorage\Domain\Entity\File;
use Module\Support\FileStorage\Domain\Service\PathGeneratorInterface;
use Module\Support\FileStorage\Domain\Service\UrlGeneratorInterface;

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
