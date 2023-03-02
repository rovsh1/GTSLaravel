<?php

namespace Gsdk\Filemanager\Services;

use Illuminate\Support\Facades\File;

class PathReader
{
    private array $files = [];

    private int $count = 0;

    public function __construct(private readonly StorageService $storageService) {}

    public function read(string $path, int $offset, int $limit): void
    {
        $readPath = $this->storageService->path($path);
        $handle = opendir($readPath);
        while (false !== ($entry = readdir($handle))) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $this->count++;

            if ($offset > $this->count || count($this->files) >= $limit) {
                continue;
            }

            $filename = $readPath . DIRECTORY_SEPARATOR . $entry;
            $file = new \stdClass();
            $file->name = $entry;
            $file->path = $path;
            if (is_dir($filename)) {
                $file->type = 'folder';
            } else {
                $file->type = 'file';
                $file->size = File::size($filename);
                $file->mime_type = File::mimeType($filename);
            }

            $this->files[] = $file;
            //$step--;
        }
        closedir($handle);
    }

    public function files(): array
    {
        return $this->files;
    }

    public function count(): int
    {
        return $this->count;
    }

    public function sort(): void
    {
        usort($this->files, function ($a, $b) {
            if ($a->type === $b->type) {
                return $a->name > $b->name ? 1 : -1;
            } elseif ($a->type === 'folder') {
                return -1;
            } else {
                return 1;
            }
        });
    }
}
