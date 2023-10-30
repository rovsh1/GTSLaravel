<?php

namespace Module\Support\FileStorage\Infrastructure\Repository;

use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use Module\Support\FileStorage\Domain\Entity\File;
use Module\Support\FileStorage\Domain\Repository\CacheRepositoryInterface;
use Module\Support\FileStorage\Domain\ValueObject\Guid;

class CacheRepository implements CacheRepositoryInterface
{
    private const CACHE_PREFIX = 'files';

    private Connection $connection;

    public function get(Guid $guid): ?File
    {
        $data = $this->connection()->hget(self::CACHE_PREFIX, $guid->value());

        return empty($data) ? null : self::unpack($data);
    }

    public function store(File $file): void
    {
        $this->connection()->hset(
            self::CACHE_PREFIX,
            $file->guid()->value(),
            self::pack($file)
        );
    }

    public function forget(File $file): void
    {
        $this->connection()->hdel(self::CACHE_PREFIX, $file->guid()->value());
    }

    private static function pack(File $file): string
    {
        return json_encode($file->serialize());
    }

    private static function unpack(string $encoded): File
    {
        $data = json_decode($encoded, true);

        return File::deserialize($data);
    }

    private function connection(): Connection
    {
        return $this->connection
            ?? ($this->connection = Redis::connection('cache'));
    }
}
