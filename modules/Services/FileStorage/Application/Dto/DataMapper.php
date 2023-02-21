<?php

namespace Module\Services\FileStorage\Application\Dto;

use Module\Services\FileStorage\Domain\Entity\File;
use Module\Services\FileStorage\Domain\Service\UrlGeneratorInterface;

class DataMapper
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator
    ) {}

    public function fileToDto(File $file): FileDto
    {
        return new FileDto(
            $file->guid(),
            $file->type(),
            $file->entityId(),
            $file->name(),
            $this->urlGenerator->url($file->guid())
        );
    }
}
