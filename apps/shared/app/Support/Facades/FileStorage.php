<?php

namespace App\Shared\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Module\Shared\Infrastructure\Adapter\FileStorageAdapter;
use Module\Support\FileStorage\Application\Dto\FileInfoDto;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Dto\FileDto;

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
