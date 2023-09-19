<?php

namespace Module\Support\FileStorage\Domain\Repository;

use Module\Support\FileStorage\Domain\Entity\File;
use Module\Support\FileStorage\Domain\ValueObject\Guid;

interface CacheRepositoryInterface
{
    public function get(Guid $guid): ?File;

    public function store(File $file): void;

    public function forget(File $file): void;
}
