<?php

namespace Module\Services\FileStorage\Domain\Service;

class UrlGenerator implements UrlGeneratorInterface
{
    public function __construct(
        private readonly string $baseUrl
    ) {}

    public function url(string $guid, int $part = null): string
    {
        return $this->baseUrl . '/file/' . $guid . ($part ? '/' . $part : '');
    }
}
