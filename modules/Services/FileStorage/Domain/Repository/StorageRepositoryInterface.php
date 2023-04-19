<?php

namespace Module\Services\FileStorage\Domain\Repository;

use Module\Services\FileStorage\Application\Dto\FileInfoDto;
use Module\Services\FileStorage\Domain\Entity\File;

interface StorageRepositoryInterface
{
    public function get(File $file, int $part = null): ?string;

    public function put(File $file, string $contents): bool;

    public function delete(File $file): bool;

    public function fileInfo(File $file, int $part = null): FileInfoDto;
}
