<?php

namespace App\Shared\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Dto\FileDto;
use Sdk\Shared\Dto\FileInfoDto;
use Shared\Support\Adapter\FileStorageAdapter;

/**
 * @method static FileDto|null find(string $guid)
 * @method static FileDto create(string $name, string $contents)
 * @method static void update(string $guid, string $name, string $contents)
 * @method static FileInfoDto|null getInfo(string $guid)
 * @method static void delete(string $guid)
 *
 * @see FileStorageAdapter
 */
class FileStorage extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FileStorageAdapterInterface::class;
    }
}
