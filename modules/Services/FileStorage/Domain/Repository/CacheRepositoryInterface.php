<?php

namespace Module\Services\FileStorage\Domain\Repository;

use Module\Services\FileStorage\Domain\Entity\File;

interface CacheRepositoryInterface
{
    public function get(string $guid): ?File;

    public function store(File $file): void;

    public function forget(File $file): void;
}
