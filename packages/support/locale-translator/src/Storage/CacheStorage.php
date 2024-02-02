<?php

namespace Support\LocaleTranslator\Storage;

use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;

class CacheStorage
{
    private const CACHE_KEY = 'locale-dictionary';

    private readonly Connection $connection;

    public function __construct()
    {
        $this->connection = Redis::connection('cache');
    }

    public function get(string $locale): ?array
    {
        $data = $this->connection->hGetAll($this->cacheId($locale));

        return empty($data) ? null : $data;
    }

    public function put(string $locale, array $items): void
    {
        foreach ($items as $key => $value) {
            $this->connection->hSet($this->cacheId($locale), $key, $value);
        }
    }

    public function delete(string $locale): void
    {
        $this->connection->del($this->cacheId($locale));
    }

    private function cacheId(string $locale): string
    {
        return self::CACHE_KEY . ":$locale";
    }
}
