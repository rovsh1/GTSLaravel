<?php

namespace App\Admin\Components\Factory;

class PrototypeLoader
{
    public function __construct(
        private readonly string $resourcesPath,
        private readonly PrototypesCollection $prototypes
    ) {}

    public function load(): void
    {
        $this->scanDir('');
    }

    private function scanDir($subPath): void
    {
        $scanPath = $this->resourcesPath . DIRECTORY_SEPARATOR . $subPath;
        $handle = opendir($scanPath);
        if (!$handle) {
            return;
        }

        while (false !== ($entry = readdir($handle))) {
            if (in_array($entry, ['.', '..'])) {
                continue;
            }

            $sub = ($subPath ? $subPath . DIRECTORY_SEPARATOR : '') . $entry;
            $filename = $this->resourcesPath . DIRECTORY_SEPARATOR . $sub;

            if (is_dir($filename)) {
                $this->scanDir($sub);
            } else {
                $prototypeBuilder = require $filename;
                $this->prototypes->add($prototypeBuilder->build());
            }
        }

        closedir($handle);
    }
}
