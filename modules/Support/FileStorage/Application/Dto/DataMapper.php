<?php

namespace Module\Support\FileStorage\Application\Dto;

use Module\Support\FileStorage\Domain\Entity\File;
use Module\Support\FileStorage\Domain\Service\UrlGeneratorInterface;

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
            $this->urlGenerator->url($file)
        );
    }
}
