<?php

namespace App\Admin\Components\Prototype;

class PrototypeLoader
{
    public function __construct(private readonly string $resourcesPath) {}

    public function load(): \Generator
    {
        return $this->scanDir('');
    }

    private function scanDir($subPath): \Generator
    {
        $scanPath = $this->resourcesPath . DIRECTORY_SEPARATOR . $subPath;
        $handle = opendir($scanPath);
        if (!$handle) {
            return [];
        }

        while (false !== ($entry = readdir($handle))) {
            if (in_array($entry, ['.', '..'])) {
                continue;
            }

            $sub = ($subPath ? $subPath . DIRECTORY_SEPARATOR : '') . $entry;
            $filename = $this->resourcesPath . DIRECTORY_SEPARATOR . $sub;

            if (is_dir($filename)) {
                yield from $this->scanDir($sub);
            } else {
                yield $this->parse($filename);
            }
        }

        closedir($handle);
    }

    private function parse($filename): array
    {
        return json_decode(file_get_contents($filename), true);
    }
}
