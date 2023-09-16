<?php

namespace Module\Support\FileStorage\Domain\Repository;

use Module\Support\FileStorage\Domain\Entity\File;
use Module\Support\FileStorage\Domain\ValueObject\Guid;

interface DatabaseRepositoryInterface
{
    public function find(Guid $guid): ?File;

    public function create(string $name = null): File;

    public function store(File $file): bool;

    public function delete(File $file): bool;

    public function touch(File $file): void;
}
