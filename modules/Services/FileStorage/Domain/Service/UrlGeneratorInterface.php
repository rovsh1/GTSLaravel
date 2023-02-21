<?php

namespace Module\Services\FileStorage\Domain\Service;

interface UrlGeneratorInterface
{
    public function url(string $guid, int $part = null): string;
}
