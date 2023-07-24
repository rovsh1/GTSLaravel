<?php

namespace Module\Support\FileStorage\Infrastructure\Repository;

use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use Module\Support\FileStorage\Domain\Entity\File;
use Module\Support\FileStorage\Domain\Repository\CacheRepositoryInterface;

class CacheRepository implements CacheRepositoryInterface
{
    private const CACHE_PREFIX = 'files';

    private Connection $connection;

    public function get(string $guid): ?File
    {
        $data = $this->connection()->hget(self::CACHE_PREFIX, $guid);

        return empty($data) ? null : self::unpack($data);
    }

    public function store(File $file): void
    {
        $this->connection()->hset(
            self::CACHE_PREFIX,
            $file->guid(),
            self::pack($file)
        );
    }

    public function forget(File $file): void
    {
        $this->connection()->hdel(self::CACHE_PREFIX, $file->guid());
    }

    private static function pack(File $file): string
    {
        return json_encode([
            'guid' => $file->guid(),
            'type' => $file->type(),
            'extension' => $file->extension(),
            'entityId' => $file->entityId(),
            'name' => $file->name()
        ]);
    }

    private static function unpack(string $encoded): File
    {
        $data = json_decode($encoded, true);

        return new File(
            $data['guid'],
            $data['type'],
            $data['extension'],
            $data['entityId'],
            $data['name'],
        );
    }

    private function connection(): Connection
    {
        return $this->connection
            ?? ($this->connection = Redis::connection('cache'));
    }
}
