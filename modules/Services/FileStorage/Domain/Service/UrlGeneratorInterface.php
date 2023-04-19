<?php

namespace Module\Services\FileStorage\Domain\Service;

use Module\Services\FileStorage\Domain\Entity\File;

interface UrlGeneratorInterface
{
    public function url(File $file, int $part = null): ?string;
}
