<?php

namespace Module\Support\FileStorage\Domain\Repository;

use Module\Support\FileStorage\Application\Dto\FileInfoDto;
use Module\Support\FileStorage\Domain\Entity\File;

interface StorageRepositoryInterface
{
    public function get(File $file, int $part = null): ?string;

    public function put(File $file, string $contents): bool;

    public function delete(File $file): bool;

    public function fileInfo(File $file, int $part = null): FileInfoDto;
}
