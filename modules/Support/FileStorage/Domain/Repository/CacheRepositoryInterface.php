<?php

namespace Module\Support\FileStorage\Domain\Repository;

use Module\Support\FileStorage\Domain\Entity\File;

interface CacheRepositoryInterface
{
    public function get(string $guid): ?File;

    public function store(File $file): void;

    public function forget(File $file): void;
}
