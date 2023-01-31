<?php

namespace GTS\Services\FileStorage\Infrastructure\Facade;

use GTS\Services\FileStorage\Application\Dto\FileDto;
use GTS\Services\FileStorage\Domain\Entity\File;
use GTS\Services\FileStorage\Domain\Service\UrlGeneratorInterface;

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
