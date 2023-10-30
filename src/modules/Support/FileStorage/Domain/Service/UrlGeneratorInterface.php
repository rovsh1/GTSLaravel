<?php

namespace Module\Support\FileStorage\Domain\Service;

use Module\Support\FileStorage\Domain\Entity\File;

interface UrlGeneratorInterface
{
    public function url(File $file, int $part = null): ?string;
}
