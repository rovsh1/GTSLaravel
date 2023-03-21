<?php

namespace App\Core\Support\Facades;

use App\Core\Contracts\File\FileInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static FileInterface|null find(string|FileInterface $guid)
 * @method static FileInterface|null getEntityFile(int $entityId, string $fileType)
 * @method static Collection getEntityFiles(int $entityId, string $fileType)
 * @method static string|null getContents(string|FileInterface $guid, ?int $part)
 * @method static array fileInfo(string|FileInterface $guid, ?int $part)
 * @method static string|null url(string|FileInterface $guid, ?int $part)
 * @method static FileInterface|null create(string $fileType, ?int $entityId, string $name = null, string $contents = null)
 * @method static bool put(string|FileInterface $guid, string $contents)
 * @method static bool delete(string|FileInterface $guid)
 * @method static FileInterface|null uploadOrCreate(FileInterface|null $file, UploadedFile $uploadedFile, string $fileType, ?int $entityId)
 *
 * @see \App\Core\Support\Adapters\FileAdapter
 */
class FileAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'file-adapter';
    }
}
