<?php

namespace Module\Shared\Contracts\Adapter;

use Module\Support\FileStorage\Application\Dto\FileInfoDto;
use Sdk\Shared\Dto\FileDto;

interface FileStorageAdapterInterface
{
    public function find(string $guid): ?FileDto;

    public function create(string $name, string $contents): FileDto;

    public function update(string $guid, string $name, string $contents): void;

    public function updateOrCreate(string|null $guid, string $name, string $contents): ?FileDto;

    public function getInfo(string $guid): ?FileInfoDto;

    public function delete(string $guid): void;
}
