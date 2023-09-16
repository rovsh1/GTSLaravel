<?php

namespace Module\Support\FileStorage\Infrastructure\Service;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Module\Support\FileStorage\Domain\Entity\File;
use Module\Support\FileStorage\Domain\Service\PathGeneratorInterface;
use Module\Support\FileStorage\Domain\Service\UrlGeneratorInterface;

class UrlGenerator implements UrlGeneratorInterface
{
    private FilesystemAdapter $storage;

    public function __construct(
        string $disk,
        private readonly PathGeneratorInterface $pathGenerator
    ) {
        $this->storage = Storage::disk($disk);
    }

    public function url(File $file, int $part = null): ?string
    {
        return $this->storage->url(
            str_replace(DIRECTORY_SEPARATOR, '/', $this->pathGenerator->relativePath($file))
            . '/' . rawurlencode($file->name())
        );
    }
}
