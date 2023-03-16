<?php

namespace App\Core\Support\File;

use App\Core\Contracts\File\FileInterface;
use App\Core\Support\Facades\FileAdapter;
use Illuminate\Support\Collection;

abstract class AbstractFile implements FileInterface
{
    final public static function type(): string
    {
        return static::class;
    }

    public static function find(string $guid): ?static
    {
        $file = FileAdapter::find($guid);
        if (is_subclass_of($file, static::class)) {
            return $file;
        } else {
            return null;
        }
    }

    public static function findByEntity(int $entityId): ?static
    {
        return FileAdapter::getEntityFile($entityId, static::class);
    }

    public static function getEntityFiles(int $entityId): Collection
    {
        return FileAdapter::getEntityFiles($entityId, static::class);
    }

    public static function create(?int $entityId, string $name = null, string $contents = null): ?static
    {
        return FileAdapter::create(static::class, $entityId, $name, $contents);
    }

    public function __construct(
        private readonly string $guid,
        private readonly ?int $entityId,
        private readonly ?string $name,
        private readonly string $url
    ) {
    }

    public function guid(): string
    {
        return $this->guid;
    }

    public function entityId(): ?int
    {
        return $this->entityId;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function url(): string
    {
        return $this->url;
    }
}
